<template>

    <v-container fluid class="fill-height grey lighten-5">

        <v-row align="center" justify="center" style="width: 100%">
            <v-col cols="12" sm="8" md="4" lg="3">
                <v-card class="elevation-1 rounded-lg">
                    <v-toolbar color="primary" dark flat>
                        <v-toolbar-title class="subtitle-1 font-weight-bold text-uppercase">
                            {{ $t('login.title') }}
                        </v-toolbar-title>
                    </v-toolbar>
                    <v-card-text class="pa-6">
                        <ValidationObserver ref="observer" v-slot="{ invalid }">
                            <v-form @submit.prevent="submit">

                                <ValidationProvider v-slot="{ errors }" name="email" rules="required|email">
                                    <v-text-field v-model="email" :label="$t('login.email')"
                                        prepend-inner-icon="mdi-email-outline" type="email" outlined dense
                                        :error-messages="errors"></v-text-field>
                                </ValidationProvider>

                                <ValidationProvider v-slot="{ errors }" name="password" rules="required">
                                    <v-text-field v-model="password" :label="$t('login.password')"
                                        prepend-inner-icon="mdi-lock-outline" type="password" outlined dense
                                        :error-messages="errors"></v-text-field>
                                </ValidationProvider>

                                <v-alert v-if="error" type="error" dense outlined class="mt-2 caption">
                                    {{ error }}
                                </v-alert>

                                <v-btn block color="primary" large type="submit" :loading="loading" :disabled="invalid"
                                    class="mt-4">
                                    {{ $t('login.submit') }}
                                </v-btn>
                            </v-form>
                        </ValidationObserver>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data: () => ({
        email: '',
        password: '',
        loading: false,
        error: null
    }),
    methods: {
        async submit() {
            const isValid = await this.$refs.observer.validate();
            if (!isValid) return;

            this.loading = true;
            this.error = null;

            try {
                await this.$store.dispatch('auth/login', {
                    email: this.email,
                    password: this.password
                });
                this.$router.push('/');
            } catch (err) {
                this.error = this.$t('login.error');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
