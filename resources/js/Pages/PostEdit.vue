<template>
    <div class="main">
        <PageHeader :title="$route.meta.title" />

        <main class="container-fluid" v-show="isReady">
            <!-- toolbar -->
            <div
                class="d-flex mb-4 pb-4 border-bottom"
                :class="
                    $route.meta.type == 1
                        ? 'justify-content-between'
                        : 'justify-content-end'
                "
                v-if="isReady"
            >
                <div>
                    <router-link
                        type="button"
                        class="btn btn-primary btn-sm"
                        v-if="$route.meta.type == 1"
                        :to="{ name: 'selector', params: { id: id } }"
                    >
                        Videos
                    </router-link>
                    <span class="mx-2"></span>
                    <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        @click="deletePost"
                    >
                        Delete
                    </button>
                    <span class="mx-2"></span>
                    <button
                        type="button"
                        class="btn btn-primary btn-sm"
                        @click="savePost"
                    >
                        Save
                    </button>
                </div>
                <button
                    type="button"
                    class="btn btn-success btn-sm"
                    @click="openPublishModal"
                    v-if="$route.meta.type == 1"
                >
                    Publish
                </button>
            </div>
            <!-- form -->
            <form id="postform" class="mb-5">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="postTitle" class="form-label"
                                >Title</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="postTitle"
                                placeholder="Post title .."
                                v-model="post.title"
                            />
                        </div>
                        <div class="mb-3">
                            <label for="postSlug" class="form-label"
                                >Slug</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="postSlug"
                                placeholder="Post slug .."
                                v-model="post.slug"
                            />
                        </div>
                        <div class="mb-3">
                            <label for="postImage" class="form-label"
                                >Featured Image</label
                            >
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Select feature image"
                                    v-model="post.featured_image"
                                    readonly
                                />
                                <button
                                    class="btn btn-secondary"
                                    type="button"
                                    id="button-addon2"
                                    @click="openImageModal()"
                                >
                                    New Image
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="seoTitle" class="form-label"
                                >Seo Title</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="seoTitle"
                                placeholder="Post seo title .."
                                v-model="post.seo_title"
                            />
                        </div>
                        <div class="mb-3">
                            <label for="seoDesc" class="form-label"
                                >Seo Description</label
                            >
                            <textarea
                                class="form-control"
                                id="seoDesc"
                                rows="5"
                                placeholder="Write seo description .."
                                v-model="post.seo_desc"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <div class="row mb-4" v-if="$route.meta.type == 1">
                    <div class="col-12 col-md-6">
                        <VueMultiselect
                            v-model="post.categories"
                            :options="categories"
                            :multiple="true"
                            :taggable="true"
                            :close-on-select="false"
                            label="title"
                            track-by="id"
                        >
                        </VueMultiselect>
                    </div>
                    <div class="col-12 col-md-6">
                        <VueMultiselect
                            v-model="post.tags"
                            :options="tags"
                            :multiple="true"
                            :taggable="true"
                            :close-on-select="false"
                            label="title"
                            track-by="id"
                        >
                        </VueMultiselect>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label for="postBody" class="form-label"
                            >Posts Content</label
                        >
                        <VueEditor
                            :editorToolbar="customToolbar"
                            v-model="post.body"
                            id="postBody"
                        />
                    </div>
                </div>
            </form>
        </main>
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
    </div>
</template>
<script>
import PageHeader from "../components/Header";
import VueMultiselect from "vue-multiselect";
import _get from "lodash/get";
import NProgress from "nprogress";
import { VueEditor } from "vue3-editor";
import PostPublishModal from "../components/modals/PostPublishModal";
import ImageAddModal from "../components/modals/ImageAddModal";

export default {
    name: "posts-edit",

    components: {
        PageHeader,
        VueEditor,
        VueMultiselect,
    },

    data() {
        return {
            id: this.$route.params.id,
            post: {
                title: "",
                slug: "",
                featured_image: null,
                seo_title: null,
                seo_desc: null,
                body: null,
                published_at: null,
                categories: [],
                tags: [],
            },
            customToolbar: [
                [{ header: [false, 2, 3, 4, 5, 6] }],
                ["bold", "italic", "link"],
                [{ list: "ordered" }],
            ],
            isReady: false,
            categories: [],
            tags: [],
        };
    },

    methods: {
        getPost() {
            this.isReady = false;

            return this.request()
                .get("/posts/" + this.id)
                .then(({ data }) => {
                    this.isReady = true;
                    this.post.title = _get(data.post, "title", "");
                    this.post.slug = _get(data.post, "slug", "");
                    this.post.featured_image = _get(
                        data.post,
                        "featured_image",
                        ""
                    );
                    this.post.seo_title = _get(data.post, "seo_title", "");
                    this.post.seo_desc = _get(data.post, "seo_desc", "");
                    this.post.body = _get(data.post, "body", "");
                    this.post.categories = _get(data.post, "categories", []);
                    this.post.tags = _get(data.post, "tags", []);
                    this.post.published_at = _get(
                        data.post,
                        "published_at",
                        ""
                    );
                    this.categories = _get(data, "categories", []);
                    this.tags = _get(data, "tags", []);
                })
                .catch((err) => {
                    this.$router.go(-1);
                });
        },

        savePost() {
            NProgress.start();
            this.request()
                .post("/posts/" + this.id, this.post)
                .then((data) => {
                    NProgress.done();
                })
                .catch((err) => {
                    NProgress.done();
                });
        },

        publishPost(data) {
            this.post.published_at = data;
            this.$vbsModal.close();
            this.savePost();
        },

        openPublishModal() {
            if (this.$route.meta.type != 1) return;

            this.$vbsModal.open({
                content: PostPublishModal,
                staticBackdrop: true,
                center: true,
                contentProps: {
                    published_date: this.post.published_at,
                },
                contentEmits: {
                    onUpdate: this.publishPost,
                },
            });
        },

        openImageModal() {
            this.$vbsModal.open({
                content: ImageAddModal,
                staticBackdrop: true,
                center: true,
                contentProps: {
                    featureImage: this.post.featured_image,
                    id: this.id,
                },
                contentEmits: {
                    onUpdate: () => {
                        this.getPost();
                    },
                },
            });
        },

        deletePost() {
            this.$vbsModal
                .confirm({
                    title: "Delete Post",
                    message: "Do you want to delete this post ?",
                    center: true,
                    rightBtnText: "Delete",
                })
                .then((data) => {
                    if (!data) return;

                    NProgress.start();
                    this.request()
                        .delete("/posts/" + this.id)
                        .then((data) => {
                            NProgress.done();
                            this.$router.go(-1);
                        })
                        .catch((error) => {
                            NProgress.done();
                            console.log(error);
                        });
                });
        },
    },

    created() {
        this.getPost();
    },
};
</script>
