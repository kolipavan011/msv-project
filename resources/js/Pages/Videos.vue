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
                            v-if="canSelect && !canPaste"
                            @click="moveFolder()"
                        >
                            Move
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm"
                            v-if="canPaste"
                            @click="pasteFolder()"
                        >
                            Paste
                        </button>
                        <button
                            type="button"
                            class="btn btn-secondary btn-sm ms-2"
                            v-show="canPaste || canSelect"
                            @click="clearSelection()"
                        >
                            Clear
                        </button>
                    </div>
                </div>
                <div class="col-6 text-end">
                    <div
                        class="btn-group"
                        role="group"
                        aria-label="Basic example"
                    >
                        <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            @click="deleteFolder"
                        >
                            Delete
                        </button>
                        <button
                            type="button"
                            class="btn btn-warning btn-sm"
                            @click="createFolder()"
                        >
                            New Folder
                        </button>
                    </div>
                </div>
            </div>

            <!-- breadcrub -->
            <div class="row mb-3">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li
                                class="breadcrumb-item"
                                v-for="dir in folderTrack"
                            >
                                <a>{{ dir.title }}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- video list -->
            <div
                class="row row-cols-2 row-cols-sm-4 row-cols-md-4 row-cols-lg-6 g-3 mb-4"
                v-if="isReady"
            >
                <div class="col" v-show="folderTrack.length > 1">
                    <div class="card border-0" @click="prevFolder()">
                        <img src="/storage/prev-folder.jpg" />
                        <div class="card-body">
                            <p class="card-text text-truncate text-center">
                                <small><strong>Go Back</strong></small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col" v-for="item in videos">
                    <div
                        class="card border border-primary"
                        :class="item.isSelected ? 'border-4' : 'border-0'"
                        @click="openFolder(item)"
                    >
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
import FolderAddModal from "../components/modals/FolderAddModal.vue";
import VideoViewModal from "../components/modals/VideoViewModal.vue";

const _path = "/storage/next-folder.jpg";

export default {
    name: "home",

    components: {
        PageHeader,
    },

    data() {
        return {
            canSelect: false,
            canPaste: false,
            isReady: false,
            videos: [],
            selectedItem: [],
            folderTrack: [{ id: 1, title: "Home" }],
        };
    },

    methods: {
        fetchVideos() {
            this.isReady = false;

            let folder_id = this.folderTrack[this.folderTrack.length - 1].id;

            this.request()
                .get("/videos/" + folder_id)
                .then(({ data }) => {
                    this.isReady = true;
                    this.videos = data.map((i) => {
                        i.isSelected = false;
                        i.type = i.poster ? "file" : "folder";
                        i.poster = i.poster ? i.poster : _path;
                        return i;
                    });
                })
                .catch((error) => {
                    this.isReady = true;
                    console.log(error);
                });
        },

        openFolder(folder) {
            if (folder.type == "folder") {
                this.folderTrack.push(folder);
                this.fetchVideos();
                return;
            }

            if (this.canSelect && !this.canPaste) {
                folder.isSelected = !folder.isSelected;
                return;
            }

            this.$vbsModal.open({
                content: VideoViewModal,
                staticBackdrop: true,
                center: true,
                contentProps: {
                    media: folder,
                },
                contentEmits: {
                    onDelete: () => {
                        this.fetchVideos();
                    },
                },
            });
        },

        prevFolder() {
            this.folderTrack.pop();
            this.fetchVideos();
        },

        createFolder() {
            let folder_id = this.folderTrack[this.folderTrack.length - 1].id;

            this.$vbsModal.open({
                content: FolderAddModal,
                staticBackdrop: true,
                center: true,
                contentProps: {
                    folder: folder_id,
                },
                contentEmits: {
                    onCreate: () => {
                        this.$vbsModal.close();
                        this.fetchVideos();
                    },
                },
            });
        },

        moveFolder() {
            this.canPaste = true;
            this.videos.map((video) => {
                if (video.type == "file" && video.isSelected) {
                    this.selectedItem.push(video.id);
                }
            });
        },

        pasteFolder() {
            let folder_id = this.folderTrack[this.folderTrack.length - 1].id;

            this.isReady = false;
            this.request()
                .post("/videos/paste/" + folder_id, {
                    folders: this.selectedItem,
                })
                .then(() => {
                    this.selectedItem.length = 0;
                    this.canPaste = false;
                    this.canSelect = false;
                    this.fetchVideos();
                    this.$toast.success("Videos moved successfully");
                })
                .catch(({ response }) => {
                    this.$toast.error(response.statusText);
                    this.isReady = true;
                });
        },

        deleteFolder(e) {
            e.target.innerText = "Deleting";

            let folder_id = this.folderTrack[this.folderTrack.length - 1].id;

            this.request()
                .delete("/folders/" + folder_id)
                .then((data) => {
                    e.target.innerText = "Delete";
                    this.prevFolder();
                    console.log(data);
                })
                .catch((error) => {
                    e.target.innerText = "Delete";
                    console.log(error);
                });
        },

        clearSelection() {
            this.videos.forEach((item) => {
                item.isSelected = false;
            });

            this.canPaste = false;
            this.canSelect = false;
        },
    },

    mounted() {
        this.fetchVideos();
    },
};
</script>
