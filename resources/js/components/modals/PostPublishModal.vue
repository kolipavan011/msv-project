<template>
    <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Publish Post</h5>
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
            @click="close"
        ></button>
    </div>
    <div class="modal-body">
        <label for="input1" class="form-label d-none">Date</label>
        <input
            type="datetime-local"
            class="form-control border-0"
            id="published_at"
            style="outline: 0"
            v-model="result_date"
        />
    </div>
    <div class="modal-footer">
        <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
            @click="close"
        >
            Cancel
        </button>
        <button type="button" class="btn btn-primary" @click="submitDate">
            Publish
        </button>
    </div>
</template>
<script>
export default {
    name: "PostPublishModal",

    emits: ["onUpdate"],

    props: {
        published_date: {
            type: [String, null],
            required: true,
        },
    },

    data() {
        return {
            result_date: null,
        };
    },

    methods: {
        submitDate() {
            this.$emit("onUpdate", this.result_date);
        },
        close() {
            this.$vbsModal.close();
        },
    },

    created() {
        this.result_date = this.published_date;
    },
};
</script>
