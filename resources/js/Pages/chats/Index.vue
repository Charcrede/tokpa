<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Icon from '@/Icons/Icon.vue'
import { Link } from '@inertiajs/vue3';
    export default{
        props:{
            messages : Array
        },
        components:{
    AuthenticatedLayout,
    Icon,
    Link
},
        data(){

        },
        mounted(){
            this.dater()
        },
        methods:{
            dater(str){
                let today = new Date('YYYY-mm-dd');
                console.log(today);
            },
            formater(str){
                if (str.length > 30) {
                    return str.substring(0,30) + '...';
                }else{
                    return str
                }
            },
            nameFormater(str){
                if (str.length > 17) {
                    return str.substring(0,17) + '...';
                }else{
                    return str
                }
            }

        }
    }
</script>

<template>
    <AuthenticatedLayout>
        <div class="h-[90vh]">
            <h3 class="font-bold text-2xl mb-4">Messages</h3>
            <Link v-for="msg,i in messages" :key="i" class="flex h-14 items-center border-b" :href="'/chats/boutique/'+ msg.message.conv_id">
                <div class="overflow-hidden flex justify-center items-center border-4 border-[#008e97] h-12 w-12 rounded-full mx-4"><Icon :name="'user'" height="60" width="60" fill="#008e97"></Icon></div>
                <div class="w-[70%]">
                    <span class="font-semibold text-xl">{{ nameFormater(msg.user.firstname+ " " + msg.user.lastname) }}</span>
                    <span class="text-[#008e97] float-right">{{ new Date(msg.message.created_at).toLocaleDateString() }}</span>
                    <p>{{ formater(msg.message.message) }}</p>
                </div>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>