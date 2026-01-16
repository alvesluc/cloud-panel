<template>
    <div>
        <div v-if="loading">
            <v-row>
                <v-col cols="12" md="4" v-for="n in 3" :key="n">
                    <v-skeleton-loader type="article" class="elevation-2 rounded-lg"></v-skeleton-loader>
                </v-col>
            </v-row>
            <v-row class="mt-4">
                <v-col cols="12">
                    <v-skeleton-loader type="table-heading, list-item-two-line@3"
                        class="elevation-2"></v-skeleton-loader>
                </v-col>
            </v-row>
            <div class="text-center mt-4 grey--text">{{ $t('common.loading') }}</div>
        </div>

        <v-row v-else-if="error" justify="center" align="center" style="height: 400px">
            <v-col cols="12" class="text-center">
                <v-icon size="64" color="grey lighten-1">mdi-cloud-off-outline</v-icon>
                <h3 class="text-h5 grey--text mt-4">{{ $t('common.error_title') }}</h3>
                <p class="grey--text text--lighten-1">{{ error }}</p>
                <v-btn color="primary" @click="fetchDashboardData">{{ $t('common.try_again') }}</v-btn>
            </v-col>
        </v-row>

        <v-row v-else-if="isEmpty" justify="center" align="center" style="height: 400px">
            <v-col cols="12" class="text-center">
                <v-icon size="64" color="blue lighten-4">mdi-chart-box-outline</v-icon>
                <h3 class="text-h5 grey--text mt-4">{{ $t('common.no_data_title') }}</h3>
                <p class="grey--text text--lighten-1">{{ $t('common.no_data_desc') }}</p>
            </v-col>
        </v-row>

        <div v-else>
            <v-row>
                <v-col cols="12" md="4">
                    <v-card class="pa-4 rounded-lg fill-height" elevation="2">
                        <div class="text-overline grey--text">{{ $t('kpi.mrr') }}</div>
                        <div class="text-h4 font-weight-bold primary--text">
                            {{ executive_summary.mrr | currency }}
                        </div>

                        <v-chip class="mt-3" label small
                            :color="executive_summary.growth_percentage >= 0 ? 'green lighten-5' : 'red lighten-5'">
                            <v-icon left small :color="executive_summary.growth_percentage >= 0 ? 'green' : 'red'">
                                {{ executive_summary.growth_percentage >= 0 ? 'mdi-trending-up' : 'mdi-trending-down' }}
                            </v-icon>
                            <span :class="executive_summary.growth_percentage >= 0 ? 'green--text' : 'red--text'">
                                {{ executive_summary.growth_percentage }}% {{ $t('kpi.growth') }}
                            </span>
                        </v-chip>
                    </v-card>
                </v-col>

                <v-col cols="12" md="4">
                    <v-card class="pa-4 rounded-lg fill-height" elevation="2">
                        <div class="text-overline grey--text">{{ $t('kpi.run_rate') }}</div>
                        <div class="text-h4 font-weight-bold grey--text text--darken-2">
                            {{ executive_summary.projected_revenue | currency }}
                        </div>
                        <v-progress-linear value="75" color="info" height="6" rounded class="mt-4"></v-progress-linear>
                        <div class="caption grey--text mt-1">{{ $t('kpi.projection_sub') }}</div>
                    </v-card>
                </v-col>

                <v-col cols="12" md="4">
                    <v-card class="fill-height rounded-lg" elevation="2" border="left" color="orange lighten-5">
                        <v-card-title class="subtitle-2 font-weight-bold deep-orange--text">
                            <v-icon left color="deep-orange" small>mdi-bell-ring</v-icon>
                            {{ $t('kpi.actions') }}
                        </v-card-title>
                        <v-list dense class="transparent">
                            <v-list-item v-for="(action, i) in actions" :key="i" v-if="i < 3">
                                <v-list-item-content>
                                    <v-list-item-title class="font-weight-medium">{{ action.message
                                        }}</v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                    <v-btn x-small color="deep-orange" outlined>{{ action.action_text }}</v-btn>
                                </v-list-item-action>
                            </v-list-item>
                            <v-list-item v-if="actions.length === 0">
                                <v-list-item-content class="grey--text caption">
                                    {{ $t('kpi.no_actions') }}
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </v-card>
                </v-col>
            </v-row>

            <v-row class="mt-6">
                <v-col cols="12">
                    <v-card elevation="2">
                        <v-card-title>{{ $t('kpi.revenue_cat') }}</v-card-title>
                        <v-simple-table>
                            <thead>
                                <tr>
                                    <th class="text-left">{{ $t('common.service') }}</th>
                                    <th class="text-left">{{ $t('common.revenue') }}</th>
                                    <th class="text-left">{{ $t('common.share') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in top_services" :key="item.service_name">
                                    <td>{{ item.service_name }}</td>
                                    <td>{{ item.revenue | currency }}</td>
                                    <td style="width: 200px">
                                        <v-progress-linear :value="calculateShare(item.revenue)" color="blue"
                                            height="10" rounded></v-progress-linear>
                                        <span class="caption grey--text">{{ calculateShare(item.revenue).toFixed(1)
                                            }}%</span>
                                    </td>
                                </tr>
                            </tbody>
                        </v-simple-table>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: true,
            error: null,
            executive_summary: { mrr: 0, growth_percentage: 0, projected_revenue: 0, mrr_context: '' },
            actions: [],
            top_services: []
        };
    },
    filters: {
        currency(value) {
            if (!value) return 'R$ 0,00';
            return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
        }
    },
    computed: {
        filters() {
            return this.$store.getters.currentFilters;
        },
        isEmpty() {
            return !this.loading && !this.error && this.executive_summary.mrr === 0 && this.top_services.length === 0;
        }
    },
    watch: {
        filters: {
            deep: true,
            handler: 'fetchDashboardData'
        }
    },
    mounted() {
        this.fetchDashboardData();
    },
    methods: {
        fetchDashboardData() {
            this.loading = true;
            this.error = null;

            this.$http.get('dashboard/overview', { params: this.filters })
                .then(response => {
                    console.log(response.body)

                    const data = response.body;
                    this.executive_summary = data.executive_summary;
                    this.actions = data.actions;
                    this.top_services = data.top_services;
                    this.loading = false;
                })
                .catch(err => {
                    console.error("Dashboard Error", err);
                    this.error = "Could not retrieve cloud metrics. Please check your connection.";
                    this.loading = false;
                });
        },
        calculateShare(revenue) {
            if (!this.executive_summary.mrr || this.executive_summary.mrr === 0) return 0;
            return (revenue / this.executive_summary.mrr) * 100;
        }
    }
}
</script>
