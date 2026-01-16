<template>
    <v-app>
        <template v-if="!isPublicRoute">
            <v-navigation-drawer app v-model="drawer" color="white" elevation="1">
                <v-list-item class="px-2 py-2">
                    <v-list-item-avatar color="primary" rounded>
                        <span class="white--text font-weight-bold">SK</span>
                    </v-list-item-avatar>
                    <v-list-item-title class="title primary--text">
                        Skynova
                    </v-list-item-title>
                </v-list-item>

                <v-divider></v-divider>

                <v-list dense nav>
                    <v-list-item to="/" link exact color="primary">
                        <v-list-item-icon><v-icon>mdi-view-dashboard</v-icon></v-list-item-icon>
                        <v-list-item-content><v-list-item-title>{{ $t('kpi.dashboard') || 'Dashboard'
                                }}</v-list-item-title></v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-navigation-drawer>

            <v-app-bar app color="grey lighten-5" flat>
                <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
                <v-spacer></v-spacer>

                <v-select :items="regionOptions" label="Região" dense outlined hide-details class="mr-4"
                    style="max-width: 180px" v-model="regionFilter" background-color="white" item-text="text"
                    item-value="value"></v-select>

                <v-btn-toggle v-model="periodFilter" mandatory dense class="mr-4" color="primary"
                    background-color="white">
                    <v-btn value="Q1">Q1</v-btn>
                    <v-btn value="Q2">Q2</v-btn>
                    <v-btn value="Q3">Q3</v-btn>
                </v-btn-toggle>

                <v-btn icon @click="logout"><v-icon>mdi-logout</v-icon></v-btn>
            </v-app-bar>
        </template>

        <v-main class="grey lighten-5">
            <v-container fluid :class="{ 'pa-0': isPublicRoute, 'pa-6': !isPublicRoute }">
                <router-view></router-view>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
export default {
    data: () => ({
        drawer: true,
        regions: [
            'SP', 'RJ', 'MG', 'MT', 'AM', 'BA', 'PE', 'RS', 'MS', 'PR', 'DF',
            'ES', 'GO', 'SC', 'PI', 'CE', 'PA', 'RN', 'MA', 'PB', 'SE', 'AL',
            'TO', 'AC', 'RO', 'RR', 'AP'
        ],
    }),
    computed: {
        regionOptions() {
            return [
                { text: '*', value: null },
                ...this.regions.map(r => ({ text: r, value: r }))
            ];
        },
        isPublicRoute() {
            return this.$route.matched.some(record => record.meta.public);
        },
        regionFilter: {
            get() { return this.$store.state.filters.region },
            set(val) { this.$store.commit('SET_REGION', val) }
        }
    },
    methods: {
        logout() {
            this.$store.dispatch('auth/logout');
            this.$router.push('/login');
        }
    }
}
</script>
