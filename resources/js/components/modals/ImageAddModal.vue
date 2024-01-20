<template>
    <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">
            Upload Feature Image
        </h5>
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
            @click="close"
        ></button>
    </div>
    <div class="modal-body">
        <div class="image-preview">
            <img :src="featureImage" width="100%" />
        </div>
        <file-pond
            ref="pond"
            acceptedFileTypes="image/*"
            :server="getServerOptions()"
            :allow-multiple="false"
            :files="files"
        />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" @click="removeImage">
            Remove
        </button>
        <button type="button" class="btn btn-primary" @click="close()">
            Done
        </button>
    </div>
</template>
<script>
// Import Vue FilePond
import vueFilePond from "vue-filepond";

// Import FilePond styles
import "filepond/dist/filepond.min.css";
// Create component
const FilePond = vueFilePond();

export default {
    name: "FolderAddModal",

    props: {
        featureImage: { type: String, required: true },
        id: { type: String, required: true },
    },

    emits: ["onUpdate"],

    components: {
        FilePond,
    },

    data() {
        return {
            files: [],
        };
    },

    methods: {
        getServerOptions() {
            return {
                url: "/api/upload/" + this.id,
                headers: {
                    "X-CSRF-TOKEN": document.head.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                fetch: null,
                delete: null,
            };
        },

        removeImage(evt) {
            evt.target.innerText = "Removing...";
            this.request()
                .delete("/upload/" + this.id)
                .then((data) => {
                    evt.target.innerText = "Remove";
                    this.$emit("onUpdate");
                    this.$vbsModal.close();
                })
                .catch((error) => {
                    evt.target.innerText = "Remove";
                    console.log(error);
                });
        },

        close() {
            this.$emit("onUpdate");
            this.$vbsModal.close();
        },
    },
};
</script>
