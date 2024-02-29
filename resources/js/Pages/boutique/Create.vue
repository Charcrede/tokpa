<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import axios from "axios";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import Swal from "sweetalert2";
export default {
    props: {

    },
    components: {
        AuthenticatedLayout,
        InputLabel,
        PrimaryButton,
        PrimaryLink
    },
    data() {
        return {
            form: {
                name: '',
                description: '',
                category: '',
                address: '',
                logo: '',
                banner: '',
                telephone: '',
                email: '',
                site: '',
            },
            current: 1,
            boutiques : '',
            div : '',
            options : ["maison et meubles", "appareils ménagers", "quincaillerie et matéreux de construction", "accessoires et pièces automobils", "cadeaux et artisanat", "fournitures pour animaux", "santé et soins personnels", "épiceries", "vêtements", "sport et divertissement", "chaussures et couvre-chefs", "maternité, bébé et jouets", "matériels informatique", "beauté et soins personnels"]
        }
    },
    mounted() {
        this.div = document.getElementById('divboutique');
        axios.get('/getBoutiques').then((resp) => {
            this.boutiques = resp.data;
            console.log(this.boutiques)
        })
    },
    methods: {
        submit() {
            let form = new FormData();
            form.append('name',this.form.name);
            form.append('address',this.form.address);
            form.append('category',this.form.category);
            form.append('telephone',this.form.telephone);
            form.append('email',this.form.email);
            form.append('site',this.form.site);
            form.append('logo',this.form.logo);
            form.append('description',this.form.description);
            form.append('banner',this.form.banner);
            console.log(form)
            axios.post('/shop', form).then(() => {
                window.location.href = '/shop';
            });
        },
        next() {
            if (this.current == 1) {
                if (this.form.name != '' && this.form.address != '' && this.form.description != '' && this.form.telephone != '') {
                    this.current ++
                }else{
                    Swal.fire({
                        icon : 'error',
                        text : "Assurer vous d'avoir correctement rempli tous les champs"
                    })
                }
            }
        },
        takePicture(id){
            if(id == 'logo'){
                let input = document.getElementById('logo');
                this.form.logo = input.files[0];
            }else{
                let input = document.getElementById('banner');
                this.form.banner = input.files[0];
            }
        }
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="text-center text-xl mt-16" v-if="this.boutiques.length > 0">
            Vous avez déjà une boutique, vous ne pouvez plus en créer une autre !
            <PrimaryLink :href="'/shop'">Voir ma boutique</PrimaryLink>
        </div>
        <form v-else @submit.prevent="submit()" class="bg-[rgb(255,255,255)] p-4 border rounded-2xl m-4 header pt-16">
            <div id="divboutique" class="flex w-full gap-8 overflow-x-scroll h-full">
                <div class="min-w-full" v-if="current == 1">

                    <legend class="text-2xl mb-4 text-center font-semibold">Créer votre boutique</legend>
                    <div>
                        <InputLabel class="my-2  block w-full  text-primary font-semibold">Nom de la boutique</InputLabel>
                        <input type="text" v-model="form.name"
                        class="block border-b-[#505050] shadow-xl border-b p-2 w-full" required>
                    </div>
                    <div>
                        <InputLabel class="my-2  block w-full  text-primary font-semibold">Adresse</InputLabel>
                        <input id="input" type="text" v-model="form.address"
                            class="block border-b-[#505050] shadow-xl border-b p-2 w-full" required>
                    </div>
                    <div>
                        <InputLabel class="my-2  block w-full  text-primary font-semibold">description</InputLabel>
                        <textarea v-model="form.description"
                            class="create block border-b-[#505050] shadow-xl border-b p-2 w-full" required></textarea>
                    </div>
                    <div>
                        <InputLabel class="my-2  block w-full  text-primary font-semibold">Numéro whatsapp de la boutique</InputLabel>
                        <input id="input" type="Number" min="10000000" max="99999999" v-model="form.telephone"
                            class="block border-b-[#505050] shadow-xl border-b p-2 w-full" required>
                    </div>
                </div>
                <div class="min-w-full" v-if="current == 2">

                    <div>
                        <InputLabel class="my-2  block w-full  text-primary font-semibold">email (facultatif)</InputLabel>
                        <input pattern="[a-zA-Z0-9_.\-]+@[a-zA-Z0-9_.\-]+\.[a-z­A-Z_.\-]{2,}" type="mail" v-model="form.email"
                            class="block border-b-[#505050] shadow-xl border-b p-2 w-full">
                    </div>
                    <div>
                        <InputLabel class="my-2  block w-full  text-primary font-semibold">site web (facultatif)</InputLabel>
                        <input id="input" type="text" v-model="form.site"
                        class="block border-b-[#505050] shadow-xl border-b p-2 w-full">
                    </div>
                    <div>
                        <InputLabel class="my-2  block w-full  text-primary font-semibold">Catégorie</InputLabel>
                        <select v-model="form.category" required
                        class="block border-b-[#505050] shadow-xl border-b p-2 w-full">
                        <option v-for="opt,i in options" :key="i" :value="opt">{{ opt }}</option>
                    </select>
                    </div>
                    <div>
                        <InputLabel class="my-2  block w-full  text-primary font-semibold">Logo (falcultatif, mais important)</InputLabel>
                        <input id="logo" type="file" @change="takePicture('logo')"
                        class="block border-b-[#505050] shadow-xl border-b p-2 w-full">
                    </div>
                    <PrimaryButton>Créer</PrimaryButton>
                </div>
            </div>
            <PrimaryButton @click.prevent="next()" v-if="current != 2">Suivant</PrimaryButton>
        </form>
    </AuthenticatedLayout>
</template>