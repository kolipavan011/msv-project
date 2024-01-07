<template>
    <div class="main">
        <PageHeader :title="$route.meta.title" />

        <main class="container-fluid">
            <!-- toolbar -->
            <div class="row mb-4">
                <div class="col-6">
                    <div
                        class="btn-group"
                        role="group"
                        aria-label="Basic example"
                    >
                        <button
                            type="button"
                            class="btn btn-primary btn-sm"
                            v-if="!canSelect"
                            @click="canSelect = !canSelect"
                        >
                            Select
                        </button>
                        <button
                            type="button"
                            class="btn btn-success btn-sm"
                            v-if="canSelect"
                        >
                            Move
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm"
                            v-if="selectedItem.length > 0"
                        >
                            Paste
                        </button>
                    </div>
                </div>
                <div class="col-6 text-end">
                    <button type="button" class="btn btn-warning btn-sm">
                        New Folder
                    </button>
                </div>
            </div>
            <!-- video list -->
            <div
                class="row row-cols-2 row-cols-sm-4 row-cols-md-4 row-cols-lg-6 g-3"
                v-if="isReady"
            >
                <div class="col">
                    <div class="card border-0">
                        <img src="/storage/prev-folder.jpg" />
                        <div class="card-body">
                            <p class="card-text text-truncate text-center">
                                <small><strong>Go Back</strong></small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col" v-for="item in videos">
                    <div class="card border-0">
                        <img :src="item.poster" alt=".." />
                        <div class="card-body">
                            <p
                                class="card-text text-truncate text-center"
                                :title="item.title"
                            >
                                <small>{{ item.title }}</small>
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

const _path = "/storage/next-folder.jpg";

export default {
    name: "home",

    components: {
        PageHeader,
    },

    data() {
        return {
            canSelect: false,
            isReady: false,
            selectedItem: [],
            videos: [],
        };
    },

    methods: {
        fetchVideos(folder_id) {
            this.isReady = false;

            this.request()
                .get("/videos/" + folder_id)
                .then(({ data }) => {
                    this.isReady = true;
                    this.videos = data.map((i) => {
                        i.poster = i.poster ? i.poster : _path;
                        return i;
                    });
                    console.log(this.videos);
                })
                .catch((error) => {
                    this.isReady = true;
                    console.log(error);
                });
        },
    },

    mounted() {
        this.fetchVideos(1);
    },
};
</script>
