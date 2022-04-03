<template>
    <div class="d-flex flex-row add-comment-section mt-4 mb-4">
        <img class="img-fluid img-responsive rounded-circle mr-2" :src="'/storage/images/' + user['avatar']" width="38">
        <input v-model="comment" v-on:keyup.enter="$props.saveComment" type="text" class="form-control form-control-sm mr-3 mt-1"
               :placeholder="'Commenting as ' + user['alias']"/>
        <button class="btn btn-sm btn-primary" type="button" @click="$props.saveComment" :disabled="comment.toString().trim() === '' || isSavingComment">
            <span v-if="isSavingComment" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span v-else>Send</span>
        </button>
    </div>
</template>

<script>
import {mapState} from "vuex";
export default {
    name: "reply",
    props: ['saveComment'],
    computed:{
        // We map the products state from our vuex store for neater code when accessing.
        ...mapState(['user'])
    },
    data(){
        return {
            comment: '',
            isSavingComment: false
        }
    }
}
</script>
