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
                <div class="col" v-for="item in videos">
                    <div class="card">
                        <img src="/storage/image.jpg" alt=".." />
                        <div class="card-body">
                            <p class="card-text text-truncate">
                                Lorem ipsum dolor sit amet consectetur,
                                adipisicing elit. Libero, est?
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
            canSelect: false,
            isReady: true,
            selectedItem: [],
            videos: [1, 2, 3, 4, 5, 6, 7, 8, 9],
        };
    },
};
</script>
