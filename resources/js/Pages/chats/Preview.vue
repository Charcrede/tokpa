<script>
import Icon from '@/Icons/Icon.vue';
export default {
    props: {
        blob: String,
        boutique: Object | String,
    },
    components:{
        Icon,
    },
    data() {
        return {
            showBlob: '',
            msg : '',
        }
    },
    mounted() {
        this.showBlob = this.blob;
    },
    methods: {
        send() {
            let link = '';
            console.log(this.boutique);
            this.boutique ? link = 'chat' : link = 'boutSend';
            axios.post(`/${link}`, {
                'msg': this.msg,
                'conv_id': this.convers[0].conv_id,
            }).then((resp) => {
                this.newConvers = resp.data
                this.msg = '';
            })
        },
    }
}   
</script>

<template v-if="showBlob !== ''">
    <div class="relative">
        <div v-if="showBlob.substring(6, 11) === 'image'"><img :src="showBlob" alt=""></div>
        <div v-if="showBlob.substring(6, 11) === 'video'"><video controls :src="showBlob" alt=""></video></div>
        <div v-if="showBlob.substring(6, 11) === 'audio'"><audio :src="showBlob" alt=""></audio></div>
        <div class="w-full flex gap-2 absolute bottom-4">
                <div class="w-[85%] flex items-between border rounded-full border-[#999]">
                    <button @click="chatgpt(file, blob)" class="rounded-full h-12 w-12 flex justify-center items-center">
                        <Icon :name="'file-image'" width="20" height="20"></Icon>
                    </button>
                    <textarea v-model="msg" class="w-[75%] h-12 overflow-none bg-[#e6e6e6] max-h-[500px] border-none"
                        placeholder="Votre message"></textarea>
                </div>
                <div class="flex items-center">
                    <button class="rounded-full h-12 w-12 flex justify-center items-center bg-[#008e97]"
                        v-if="msg.length > 0" @click="send">
                        <Icon :name="'send'" width="20" height="20" fill="white"></Icon>
                    </button>
                    <button class="rounded-full h-12 w-12 flex justify-center items-center bg-[#008e97]"
                        v-if="msg.length === 0">
                        <Icon :name="'micro'" width="20" height="20" fill="white"></Icon>
                    </button>
                </div>
            </div>
    </div>
</template>