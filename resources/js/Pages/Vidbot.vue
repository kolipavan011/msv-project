<template>
    <div class="main">
        <PageHeader :title="$route.meta.title" />

        <main class="container-fluid">
            <!-- searchbar -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="input-group mb-3">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Search videos .."
                            aria-label="Search videos .."
                            aria-describedby="button-addon2"
                            v-model="keyword"
                        />
                        <button
                            class="btn btn-primary"
                            type="button"
                            id="button-addon2"
                            @click="searchVideos"
                        >
                            Search
                        </button>
                    </div>
                </div>
            </div>

            <!-- results -->
            <div
                class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-4"
            >
                <div class="col" v-for="video in videos">
                    <div class="card h-100 bg-white">
                        <img
                            :src="video.thumbnails.high.url"
                            class="card-img-top"
                            alt="..."
                            :title="video.title"
                        />
                        <div class="timer-tip">
                            {{ video.duration }}
                        </div>
                        <div class="card-body">
                            <p class="card-text text-linebreak">
                                {{ video.title }}
                            </p>
                        </div>
                        <div class="card-footer p-0 border-0">
                            <div class="d-grid gap-2">
                                <button
                                    class="btn btn-primary btn-sm"
                                    type="button"
                                    :class="
                                        video.downloaded
                                            ? 'btn-warning'
                                            : 'btn-primary'
                                    "
                                    @click="addVideo(video)"
                                >
                                    {{
                                        video.downloaded
                                            ? "Video Added"
                                            : " Add Video"
                                    }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- pagination -->
            <div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="#">Prev</a>
                        </li>
                        <li
                            class="page-item"
                            v-for="(page, index) in pages"
                            :key="index"
                        >
                            <a
                                class="page-link"
                                href="#"
                                @click.prevent="loadVideos(index)"
                                >{{ index + 1 }}</a
                            >
                        </li>
                        <li class="page-item">
                            <a
                                class="page-link"
                                href="#"
                                @click.prevent="nextPage()"
                                v-if="pages.length > 0"
                            >
                                More Videos
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </main>
    </div>
</template>
<script>
import { YoutubeDataAPI } from "youtube-v3-api";
import extend from "lodash/extend";
import NProgress from "nprogress";
import PageHeader from "../components/Header";

const Youtube = new YoutubeDataAPI("AIzaSyBTX1j2o0wV9YC9c9VORGEC6LuQOyxiEgc");

const getDuration = function (duration) {
    return duration
        .substring(2)
        .replace("H", "h:")
        .replace("M", "m:")
        .replace("S", "s");
};

export default {
    name: "Vidbot",

    components: {
        PageHeader,
    },

    data() {
        return {
            keyword: "hanuman status video",
            videos: [],
            pages: [],
        };
    },

    methods: {
        searchVideos() {
            this.fetchVideos();
        },

        fetchVideos(params = {}) {
            let parts = extend({ type: "video" }, params);

            this.videos = [];

            NProgress.start();
            Youtube.searchAll(this.keyword, 10, parts).then(
                ({ items, nextPageToken }) => {
                    this.videos = items.map((item) => {
                        item.snippet.duration = getDuration("PT--M--S");
                        item.snippet.downloaded = false;
                        return extend(item.snippet, item.id);
                    });
                    this.pages.push({
                        items: this.videos,
                        nextPageToken: nextPageToken,
                    });
                    this.getVideoData();
                    NProgress.done();
                },
                (error) => {
                    console.log(error);
                    NProgress.done();
                }
            );
        },

        loadVideos(currentPage) {
            if (currentPage + 1 == this.pages.length) return;
            this.videos = this.pages[currentPage].items;
        },

        nextPage() {
            if (this.pages.length == 0) return;

            let currentPage = this.getCurrentPage();

            this.fetchVideos({
                pageToken: this.pages[currentPage].nextPageToken,
            });
        },

        getCurrentPage() {
            return this.pages.length - 1;
        },

        getVideoData() {
            let videoIds = this.videos.map((item) => item.videoId).toString();

            Youtube.searchVideo(videoIds).then(({ items }) => {
                this.videos.forEach((video, index) => {
                    video.duration = getDuration(
                        items[index].contentDetails.duration
                    );

                    video.tempduration = items[index].contentDetails.duration;
                });
            });
        },

        addVideo(video) {
            video.downloaded = true;
        },
    },
};
</script>

<style scoped>
.text-linebreak {
    display: -webkit-box;
    line-clamp: 3;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.timer-tip {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #000;
    color: #fff;
    font-size: 90%;
    padding: 3px 10px;
    font-weight: bold;
    border-radius: 10px;
}
</style>
