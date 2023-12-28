<template>
    <div class="main">
        <PageHeader :title="$route.meta.title" />

        <main class="container-fluid">
            <!-- Display Toolbar -->
            <div class="row justify-content-between mb-5">
                <div class="col-md-3 col-5">
                    <button
                        type="button"
                        class="btn btn-primary btn-sm"
                        @click="createPost"
                    >
                        Create New
                    </button>
                </div>
                <div class="col-md-3 col-5">
                    <select
                        class="form-select form-select-sm"
                        aria-label="Default select"
                        v-model="status"
                        @change="fetchPost(1)"
                        v-if="$route.name == 'posts'"
                    >
                        <option value="published">Published</option>
                        <option value="drafted">Drafted</option>
                    </select>
                </div>
            </div>

            <!-- Display list -->
            <div
                class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4 g-3 mb-5"
                v-show="!error"
            >
                <div class="col" v-for="post in posts">
                    <div class="card h-100">
                        <div class="card-body">
                            <router-link
                                :to="{
                                    name: $route.meta.route,
                                    params: { id: post.id },
                                }"
                                class="text-dark fw-bold"
                            >
                                <h5 class="card-title">
                                    {{ post.title }}
                                </h5>
                            </router-link>
                            <p class="card-text text-secondary fw-bold small">
                                Created on {{ post.created_at }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div
                class="d-flex justify-content-center"
                v-if="posts.length > 0 && !error"
            >
                <nav aria-label="Page navigation">
                    <ul class="pagination flex-wrap gap-2">
                        <li
                            class="page-item"
                            :class="{ active: page.active }"
                            v-for="page in pagination"
                        >
                            <a
                                class="page-link"
                                href="#"
                                v-html="page.label"
                                @click.prevent="fetchPost(page.label)"
                            ></a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Error Reporting -->
            <div class="card text-center" v-if="error" style="height: 300px">
                <div class="card-body">
                    <div
                        class="d-flex justify-content-center align-items-center h-100"
                    >
                        <div class="w-50">
                            <p>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="64"
                                    height="64"
                                    fill="currentColor"
                                    class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"
                                    viewBox="0 0 16 16"
                                    :class="errorIcon"
                                    role="img"
                                    aria-label="Warning:"
                                >
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"
                                    />
                                </svg>
                            </p>
                            <p class="card-text text-secondary">
                                {{ errorMsg }}
                            </p>
                            <button
                                type="button"
                                class="btn btn-primary btn-sm"
                                @click="fetchPost(1)"
                            >
                                Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
<script>
import NProgress from "nprogress";
import PageHeader from "../components/Header";
import _extend from "lodash/extend";
import PostPublishModal from "../components/modals/PostPublishModal";
import PostAddModal from "../components/modals/PostAddModal.vue";

const ERR_NO_POST = "No post found";
const ERR_500 = "Something went wrong ..!";

export default {
    name: "posts-list",

    components: {
        PageHeader,
    },

    data() {
        return {
            status: "published",
            posts: [],
            pagination: [],
            error: false,
            errorMsg: ERR_NO_POST,
            errorIcon: "text-danger",
        };
    },

    methods: {
        fetchPost(page = 1) {
            this.posts = [];
            this.pagination = [];
            this.error = false;
            NProgress.start();

            let _params = {
                page: page,
                type: this.$route.meta.type,
            };

            if (_params.type == 1) _extend(_params, { status: this.status });

            this.request()
                .get("/posts", {
                    params: _params,
                })
                .then(({ data }) => {
                    this.posts = data.data;
                    this.pagination = data.links;

                    if (this.posts.length == 0) {
                        this.error = true;
                        this.errorMsg = ERR_NO_POST;
                        this.errorIcon = "text-info";
                    }

                    NProgress.done();
                })
                .catch((error) => {
                    console.log(error);
                    this.error = true;
                    this.errorMsg = ERR_500;
                    this.errorIcon = "text-danger";
                    NProgress.done();
                });
        },

        createPost() {
            this.$vbsModal.open({
                content: PostAddModal,
                staticBackdrop: true,
                center: true,
            });
        },
    },

    watch: {
        async $route(to) {
            this.fetchPost();
        },
    },

    mounted() {
        this.fetchPost();
    },
};
</script>
