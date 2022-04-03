<template>
    <div class="commented-section mt-2" :class="'ml-' + margin">
        <div class="d-flex flex-row align-items-center commented-user">
            <img class="img-fluid img-responsive rounded-circle mr-2" :src="'/storage/images/' + details['user']['avatar']" width="38">
            <span class="mr-2"><small><strong>{{details['user']['alias']}}</strong></small></span>
            <span class="dot bg-success"></span>
            <span class="ml-2"><small>{{details['comment_date_time']}}</small></span>
        </div>
        <div class="ml-2">
            <span>
                <small>{{details['comment']}}</small>
            </span>
        </div>
        <div class="reply-section">
            <div class="d-flex flex-row align-items-center voting-icons">
                <span v-if="details['user']['alias'] === user['alias']" class="btn btn-sm btn-link" @click="onDelete">
                    <small>
                        <span>Delete</span>
                    </small>
                </span>
                <template v-if="level !== 3 && !isDeletingComment">
                    <span :class="{'d-none' : hideReplies, 'd-block': !hideReplies}" class="btn btn-sm btn-link" @click="hideReplies = !hideReplies">
                        <small>
                            <span>Hide Replies</span>
                        </small>
                    </span>
                    <span :class="{'d-none' : hideReplies, 'd-block': !hideReplies}" class="btn btn-sm btn-link" @click="isShowReply = !isShowReply;">
                        <small>
                            <span v-if="!isShowReply">Reply</span>
                            <span v-else>Cancel</span>
                        </small>
                    </span>
                    <span :class="{'d-none' : hideReplies, 'd-block': !hideReplies}" class="btn btn-sm btn-link" @click="retrieveReplies">
                        <small>
                            <span class="mr-2">See more...</span>
                            <!--display comment replies-->
                            <div class="spinner-border spinner-border-sm text-primary" role="status" v-if="isRetrievingReplies">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </small>
                    </span>
                    <span :class="{'d-none' : !hideReplies, 'd-block': hideReplies}" class="btn btn-sm btn-link" @click="hideReplies = !hideReplies">
                        <small>
                            <span>Show Replies</span></small>
                    </span>
                </template>
                <small v-if="isDeletingComment">
                    <!--display comment replies-->
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </small>

            </div>
        </div>
        <!--reply interface-->
        <div class="comment-bottom bg-white" v-if="isShowReply">
            <div class="d-flex flex-row add-comment-section  mt-3 mb-3">
                <img class="img-fluid img-responsive rounded-circle mr-2" :src="'/storage/images/' + user['avatar']" width="38">
                <input v-model="comment" v-on:keyup.enter="sendMessage" type="text" class="form-control form-control-sm mr-3 mt-1"
                       :placeholder="'Commenting as ' + user['alias']"/>
                <button class="btn btn-sm btn-primary" type="button" @click="sendMessage" :disabled="isSavingComment">
                    <span v-if="isSavingComment" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span v-else>Send</span>
                </button>
            </div>
        </div>
        <hr>
        <template v-for="(reply, index) in replies">
            <comment
                :key="reply.id"
                :level="level + 1"
                :details="reply"
                :margin="(level + 2)"
                :class="{'d-none' : hideReplies, 'd-block': !hideReplies}"
                :deleted="childCommentDeleted"
                :index="index"
            ></comment>
        </template>
    </div>
</template>

<script>
import {mapState} from "vuex";
export default {
    name: 'comment',
    props: ['level', 'details', 'margin', 'deleted', 'index'],
    computed:{
        // We map the products state from our vuex store for neater code when accessing.
        ...mapState(['user', 'commentsCount'])
    },
    data(){
      return{
          comment: '',
          isSavingComment: false,
          isShowReply: false,
          replies: [],
          hideReplies: false,
          isRetrievingReplies: false,
          isDeletingComment: false
      }
    },
    methods: {
        sendMessage: function(){
            this.isSavingComment = true;
            axios.post('/create_comment', {
                user_id: this.user.id,
                comment: this.comment,
                parentID: this.details.id
            }).then((response) => {
                let responseData = response.data;

                if (responseData.status === 0) {
                    if(responseData.message !== ""){
                        alert(responseData.message);
                    }else{
                        this.errors = responseData['errors'];
                    }
                } else {
                    this.$store.dispatch('setCommentsCount', this.commentsCount + 1);
                    responseData['record'].user = this.user;
                    this.replies.unshift(responseData['record']);
                    this.comment = "";
                    this.isShowReply = false;
                }
                this.isSavingComment = false;
            }).catch((error) => {
                this.isSavingComment = false;
                alert("A js error has occurred. Please contact system admin through email: familyinspiredprojects@gmail.com")
            });
        },
        retrieveReplies: function(){
            this.isRetrievingReplies = true;
            axios.post('/retrieve_child_comments', {
                parentID: this.details['id'],
                currentLength: this.replies
            }).then((response) =>{
                let responseData = response.data;
                if(responseData.status === 0){
                    alert(responseData.message);
                }else{
                    this.replies = [...this.replies, ...responseData['records']];
                }
                this.isRetrievingReplies = false;
            }).catch((error) =>{
                this.isRetrievingReplies = false;
                alert("A js error has occurred. Please contact system admin through email: familyinspiredprojects@gmail.com");
            });
        },
        onDelete: function(){
            this.isDeletingComment = true;
            axios.post('/delete_comment', {
                commentID: this.$props.details.id,
            }).then((response) =>{
                let responseData = response.data;
                if(responseData.status === 0){
                    alert(responseData.message);
                }else{
                    this.isDeletingComment = false;
                    this.$props.deleted(this.$props.index, responseData['count']);
                }
            }).catch((error) =>{
                this.isDeletingComment = false;
                alert("A js error has occurred. Please contact system admin through email: familyinspiredprojects@gmail.com");
            });
        },
        childCommentDeleted: function(index, commentCount){
            this.replies.splice(index, 1);
            this.$store.dispatch('setCommentsCount', commentCount);
        }
    },
    mounted() {
        if(this.details['replies_count'] > 0){
            this.retrieveReplies();
        }
    }
}
</script>

<style scoped>

</style>
