<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Icon from '@/Icons/Icon.vue';
import Preview from "@/Pages/chats/Preview.vue";
export default {
    props: {
        convers: Array,
        user: Object | String,
        boutique: Object | String,
        logo: Object | String,
    },
    components: {
        AuthenticatedLayout,
        Icon,
        Preview,
    },
    data() {
        return {
            ceUser: {},
            msg: '',
            newConvers: '',
            file: '',
            blob: [],
            audioChunks : [],
            mediaRecorder : '',
        }
    },
    mounted() {
        this.newConvers = this.convers;
        // this.chatGpt2();
    },
    methods: {
        getBoutiquePath(name) {
            return new URL("../../../../public/boutique_medias/" + name, import.meta.url).href
        },

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
        chatgpt(file, preview) {
            var input = document.createElement('input');
            input.type = 'file';

            input.onchange = function (e) {
                file = e.target.files[0];
                var reader = new FileReader();

                reader.onload = function () {
                    var content = reader.result;
                    console.log("Contenu du fichier:", content); // Vous pouvez faire ce que vous voulez avec le contenu du fichier ici
                    preview.push(content)
                    console.log(preview)
                };
                reader.readAsDataURL(file);
            };

            input.click();
        },
        // chatGpt2() {
            // let mediaRecorder;
            // let audioChunks = [];

            // navigator.mediaDevices.getUserMedia({ audio: true })
            //     .then(function (stream) {
            //         mediaRecorder = new MediaRecorder(stream);

            //         mediaRecorder.ondataavailable = function (e) {
            //             audioChunks.push(e.data);
            //         };

            //         mediaRecorder.onstop = function () {
            //             const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
            //             const audioUrl = URL.createObjectURL(audioBlob);
            //             const audio = new Audio(audioUrl);
            //             audio.controls = true;
            //             document.body.appendChild(audio);
            //         };
            //     })
            //     .catch(function (err) {
            //         console.error('Permission denied or microphone not available', err);
            //     });

            // document.getElementById('startRecordBtn').addEventListener('click', function () {
            //     audioChunks = [];
            //     mediaRecorder.start();
            //     document.getElementById('startRecordBtn').style.display = 'none';
            //     document.getElementById('stopRecordBtn').style.display = 'block';
            // });

            // document.getElementById('stopRecordBtn').addEventListener('click', function () {
            //     mediaRecorder.stop();
            //     document.getElementById('startRecordBtn').style.display = 'block';
            //     document.getElementById('stopRecordBtn').style.display = 'none';
            // });

        // },
        startRecord(){
            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(function (stream) {
                    this.mediaRecorder = new MediaRecorder(stream);

                    this.mediaRecorder.ondataavailable = function (e) {
                        this.audioChunks.push(e.data);
                    };

                    this.mediaRecorder.onstop = function () {
                        const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                        const audioUrl = URL.createObjectURL(audioBlob);
                        const audio = new Audio(audioUrl);
                        audio.controls = true;
                        document.body.appendChild(audio);
                    };
                })
                .catch(function (err) {
                    console.error('Permission denied or microphone not available', err);
                });
                this.mediaRecorder.start();
        }
    }
}
</script>

<template>
        <!--<Preview v-if="blob.length" v-for="bl in blob" :blob="bl" :boutique="boutique"></Preview>-->
        <div class="relative">
            <div v-for="bl in blob" class="absolute top-0 bottom-0 left-0 right-0 bg-[rgba(50,50,50,0.2)] flex items-center">
                <button @click="blob = []" class="z-50 text-3xl text-white font-bold absolute top-0 right-0"><Icon :name="'close'" width="50" height="50" fill="white"/></button>
                <div v-if="bl.substring(5, 10) === 'image'" class="flex items-center"><img :src="bl" alt=""></div>
                <div v-if="bl.substring(5, 10) === 'video'" class="flex items-center"><video controls :src="bl" alt=""></video></div>
                <div v-if="bl.substring(5, 10) === 'audio'" class="flex items-center"><audio :src="bl" alt=""></audio></div>
            </div>
            <div v-if="user" class="h-16 flex items-center gap-2 shadow-xl mb-4">
                <button>
                    <Icon :name="'arrow-left'" width="30" height="30" fill="black"></Icon>
                </button>
                <span class=" bg-[#00cdd9] h-12 w-12 rounded-full flex justify-center items-center font-bold text-white">{{
                    user.firstname.substring(0, 1) }}{{ user.lastname.substring(0, 1) }}</span>
                <span class="font-semibold text-xl">{{ user.firstname }} {{ user.lastname }}</span>
            </div>
            <div v-else class="h-16 flex items-center gap-2 shadow-xl mb-4">
                <button>
                    <Icon :name="'arrow-left'" width="30" height="30" fill="black"></Icon>
                </button>
                <img :src="getBoutiquePath(logo.path)"
                    class=" bg-[#00cdd9] h-14 w-14 border border-[#008e97] p-[0.08rem] rounded-full flex justify-center items-center font-bold text-white">
                <span class="font-semibold text-xl">{{ boutique.name }}</span>
            </div>
            <div class="w-full h-[70vh]">
                <div v-for="chat, i in newConvers" class="w-full">
                    <div class="flex justify-end" v-if="chat.sender === $page.props.auth.user.id">
                        <p class="bg-[#008e97] text-white m-2 max-w-64 rounded-3xl px-4 py-2">{{ chat.message }}</p>
                    </div>
                    <div v-else class="bg-white text-text flex justify-start max-w-64 rounded-3xl px-4 py-2 m-2">
                        {{ chat.message }}
                    </div>
                </div>
            </div>
            <div class="w-full flex gap-2 z-50 relative bottom-0">
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
        <button id="startRecordBtn" class="m-4">Commencer l'enregistrement</button>
        <button id="stopRecordBtn" style="display:none;">Arrêter l'enregistrement</button>

</template>

<style>
textarea:focus {
    --tw-ring-shadow: none;
}
</style>