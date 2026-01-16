import Vue from "vue";
import VueI18n from "vue-i18n";

Vue.use(VueI18n);

const messages = {
    en_US: {
        common: {
            loading: "Loading cloud metrics...",
            error_title: "Failed to load dashboard",
            try_again: "Try again",
            no_data_title: "No data available",
            no_data_desc:
                "Select a different region or period to view metrics.",
            service: "Service",
            revenue: "Revenue",
            share: "Impact (%)",
        },
        login: {
            title: "Access Skynova",
            email: "E-mail",
            password: "Password",
            submit: "Sign in",
            error: "Invalid credentials",
        },
        kpi: {
            dashboard: "Dashboard",
            mrr: "Monthly revenue",
            growth: "Growth",
            actions: "Pending actions",
            no_actions: "No pending actions.",
            revenue_cat: "Revenue by category",
            run_rate: "Annual run rate",
            projection_sub: "Projection based on Q1 performance",
        },
    },
    pt_BR: {
        common: {
            loading: "Carregando métricas...",
            error_title: "Falha ao carregar painel",
            try_again: "Tentar Novamente",
            no_data_title: "Sem dados disponíveis",
            no_data_desc: "Selecione outra região ou período.",
            service: "Serviço",
            revenue: "Receita",
            share: "Impacto (%)",
        },
        login: {
            title: "Acessar Skynova",
            email: "E-mail",
            password: "Senha",
            submit: "Entrar",
            error: "Credenciais inválidas",
        },
        kpi: {
            dashboard: "Dashboard",
            mrr: "Receita mensal",
            growth: "Crescimento",
            actions: "Ações pendentes",
            no_actions: "Nenhuma ação pendente.",
            revenue_cat: "Receita por categoria",
            run_rate: "Faturamento anual projetado",
            projection_sub: "Projeção baseada na performance do Q1",
        },
    },
};

export default new VueI18n({
    locale: "pt_BR",
    fallbackLocale: "en_US",
    messages,
});
