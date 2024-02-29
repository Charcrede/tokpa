<script>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';
export default {
    components: {
        AuthenticatedLayout,
        PrimaryButton
    },
    data(){
        return {
            preferences : [],
            form : useForm({
                preferences : [],
            }),
            preferencesTab : ["maison et meubles", "appareils ménagers", "quincaillerie et matéreux de construction", "accessoires et pièces automobils", "cadeaux et artisanat", "fournitures pour animaux", "santé et soins personnels", "épiceries", "vêtements", "sport et divertissement", "chaussures et couvre-chefs", "maternité, bébé et jouets", "matériels informatiques", "beauté et soins personnels"]

        }
    },
    methods:{
        select(choice,i){
            if(this.preferences[i]){
                this.preferences[i]=undefined;
            }else{
                this.preferences[i] = choice;
            }
        },
        submit(){
            for (let i = 0; i < this.preferences.length; i++) {
                if (this.preferences[i]) {
                    this.form.preferences.push(this.preferences[i]);
                }
            }
            // this.form.preferences = JSON.stringify(this.form.prefe)
            this.form.post('/preferences');
        }
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <form @submit.prevent="submit()" class=" font-semibold bg-[rgb(240,240,240)] block w-full pb-4 lg:w-1/2 rounded-2xl text-center mx-auto">
            <legend class="text-2xl font-semibold m-4 pt-4">Les articles qui pourrait vous plaires</legend>
            <ul class="text-primary">
                <li v-for="pref,i in preferencesTab" :key="i" class="inline-block m-1">
                    <button class="rounded-3xl px-4 py-2 inline border border-primary" :class="preferences[i] === pref ? 'bg-primary text-white' : ''" @click.prevent="select(pref,i)">{{ pref }}</button>
                </li>
            </ul>
            <PrimaryButton>Envoyer</PrimaryButton>
        </form>
    </AuthenticatedLayout>
</template>