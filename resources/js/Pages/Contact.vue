<script>
import  AuthenticatedLayout  from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
    export default{
        props : {
            
        },
        components : {
            AuthenticatedLayout,
            PrimaryButton,
        },
        data(){
            return {
                telephone : '',
                mount : '',
            }
        },
        mounted () {
            
        },
    }
</script>

<template>
    <AuthenticatedLayout>
        <div>
            <form class="bg-[rgb(255,255,255)] p-4 border rounded-2xl m-4 header pt-16">
                <legend class="text-2xl mb-4 text-center font-semibold">Faire un don</legend>
                    <label class="my-2  block w-full  text-[#008e97] font-semibold" for="number">Numéro de téléphone (MTN)</label>
                    <input type="number" v-model="telephone" id="number" class="block border-b-[#505050] shadow-xl border-b p-2 w-full" placeholder="ex : 62556287">
                <label class="my-2  block w-full  text-[#008e97] font-semibold" for="mount">Montant</label>
                <input type="number" v-model="mount" id="mount" class="block border-b-[#505050] shadow-xl border-b p-2 w-full" placeholder="ex : 500">
                <PrimaryButton>Donner</PrimaryButton>
            </form>
        </div>
    </AuthenticatedLayout>
</template>