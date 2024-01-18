<template>
    <div class="main">
        <header class="header mb-3">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand text-uppercase" href="#">{{
                        $route.meta.title
                    }}</a>
                    <button
                        type="button"
                        class="btn btn-primary btn-sm"
                        @click="syncVideo()"
                        :disabled="!isReady"
                    >
                        Done
                    </button>
                </div>
            </nav>
        </header>

        <main class="container-fluid">
            <!-- post info -->
            <div class="post-info mb-4">
                <p class="text-secondary" v-show="title">
                    {{ title }}
                    <span class="text-success fw-bold"
                        >( {{ selection.length }} Video selected )</span
                    >
                </p>
            </div>
            <!-- grid layout -->
            <div
                class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3 mb-5"
                v-show="isReady"
            >
                <div class="col" v-show="folderTrack.length > 1">
                    <div class="card border-0" @click="backFolder()">
                        <img src="/storage/prev-folder.jpg" />
                        <div class="card-body">
                            <p class="card-text text-truncate text-center">
                                <small><strong>Go Back</strong></small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col" v-for="item in mediaList">
                    <div
                        class="card border-primary"
                        @click="handleClick(item)"
                        :class="item.isSelected ? 'border-3' : 'border-0'"
                    >
                        <img :src="item.poster" class="card-img-top" />
                        <div class="card-body">
                            <p class="card-text text-truncate text-center">
                                {{ item.title }}
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
import extend from "lodash/extend";

const folderId = 1;
const _default = { poster: "/storage/next-folder.jpg", type: "folder" };

export default {
    name: "home",

    components: {
        PageHeader,
    },

    data() {
        return {
            id: this.$route.params.id,
            isReady: false,
            mediaList: [],
            title: null,
            folderId: folderId,
            selection: [],
            folderTrack: [folderId],
        };
    },

    methods: {
        getPost() {
            this.isReady = false;
            this.request()
                .get("/posts/" + this.id, {
                    params: {
                        videos: 1,
                    },
                })
                .then(({ data }) => {
                    this.isReady = true;
                    this.title = data.post.title;
                    this.selection = data.post.videos.map((i) => i.id);
                });
        },

        fetchMedia() {
            const id = this.folderTrack[this.folderTrack.length - 1];
            this.isReady = false;
            this.mediaList.length = 0;
            this.request()
                .get("/videos/" + id)
                .then(({ data }) => {
                    this.isReady = true;
                    this.mediaList = data.map((i) => {
                        if (!i.poster) {
                            extend(i, _default);
                        } else {
                            i.type = "file";
                            i.isSelected =
                                this.selection.indexOf(i.id) >= 0
                                    ? true
                                    : false;
                        }
                        return i;
                    });
                })
                .catch(({ response }) => {
                    this.isReady = true;
                    this.$toast.error(response.statusText);
                });
        },

        syncVideo() {
            this.isReady = false;

            this.request()
                .post("/posts/attach/" + this.id, {
                    videos: this.selection,
                })
                .then((data) => {
                    this.isReady = true;
                    this.$toast.success("Syncing Done");
                })
                .catch(({ response }) => {
                    this.isReady = true;
                    this.$toast.error(response.statusText);
                });
        },

        handleClick(media) {
            if (media.type == "folder") {
                this.folderTrack.push(media.id);
                this.fetchMedia();
            } else {
                let _index = this.selection.indexOf(media.id);
                if (_index >= 0) {
                    this.selection.splice(_index, 1);
                    media.isSelected = false;
                } else {
                    this.selection.push(media.id);
                    media.isSelected = true;
                }
            }
        },

        backFolder() {
            this.folderTrack.pop();
            this.fetchMedia();
        },
    },

    mounted() {
        this.getPost();
        this.fetchMedia();
    },
};
</script>
