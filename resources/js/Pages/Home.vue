<template>
    <div class="main">
        <PageHeader :title="$route.meta.title" />

        <main class="container-fluid">
            <div class="row mb-4" v-if="isReady">
                <!-- col -->
                <div class="col-12 col-md-3 col-sm-6 g-3">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">{{ posts }}</h2>
                            <p class="card-text text-secondary fw-bold">
                                Total Posts
                            </p>
                        </div>
                    </div>
                </div>
                <!-- col -->
                <div class="col-12 col-md-3 col-sm-6 g-3">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">{{ categories }}</h2>
                            <p class="card-text text-secondary fw-bold">
                                Total Categories
                            </p>
                        </div>
                    </div>
                </div>
                <!-- col -->
                <div class="col-12 col-md-3 col-sm-6 g-3">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">{{ tags }}</h2>
                            <p class="card-text text-secondary fw-bold">
                                Total Tags
                            </p>
                        </div>
                    </div>
                </div>
                <!-- col -->
                <div class="col-12 col-md-3 col-sm-6 g-3">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">{{ pages }}</h2>
                            <p class="card-text text-secondary fw-bold">
                                Total Pages
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- spinner -->
            <div
                class="d-flex justify-content-center align-items-center"
                v-if="!isReady"
                style="height: 300px"
            >
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </main>
    </div>
</template>
<script>
import PageHeader from "../components/Header";

export default {
    name: "home",

    components: {
        PageHeader,
    },

    data() {
        return {
            isReady: false,
            posts: 0,
            categories: 0,
            tags: 0,
            pages: 0,
        };
    },

    methods: {
        getData() {
            this.isReady = false;

            this.request()
                .get("/dashboard")
                .then(({ data }) => {
                    this.isReady = true;
                    this.posts = data.posts;
                    this.categories = data.categories;
                    this.tags = data.tags;
                    this.pages = data.pages;
                })
                .catch((error) => {
                    this.isReady = true;
                    console.log(error);
                });
        },
    },

    mounted() {
        this.getData();
    },
};
</script>
