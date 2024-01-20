<template>
    <div>
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Video</h5>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
                @click="close()"
            ></button>
        </div>
        <div class="modal-body">
            <video
                class="mb-3"
                width="100%"
                height="360px"
                :src="media.path"
                controls
            ></video>
            <div class="mb-3">
                <textarea
                    rows="5"
                    class="form-control"
                    v-model="media.title"
                ></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
                @click="close()"
            >
                Close
            </button>
            <button type="button" class="btn btn-danger" @click="deleteVideo">
                Delete
            </button>
            <button type="button" class="btn btn-primary" @click="updateVideo">
                Update
            </button>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        media: { type: Object, required: true },
    },

    emits: ["onDelete"],

    methods: {
        updateVideo(e) {
            e.target.innerText = "Updating ...";
            this.request()
                .post("videos/" + this.media.id, {
                    title: this.media.title,
                })
                .then((data) => {
                    e.target.innerText = "Update";
                    this.$toast.success("Video Updated");
                })
                .catch(({ response }) => {
                    e.target.innerText = "Update";
                    this.$toast.error(response.statusText);
                });
        },

        deleteVideo(e) {
            e.target.innerText = "Deleting ...";
            this.request()
                .delete("videos/" + this.media.id)
                .then(() => {
                    this.$emit("onDelete");
                    this.$toast.success("Video Updated");
                    this.close();
                })
                .catch(({ response }) => {
                    e.target.innerText = "Delete";
                    this.$toast.error(response.statusText);
                });
        },

        close() {
            this.$vbsModal.close();
        },
    },
};
</script>
