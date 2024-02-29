<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
export default {
    props: {

    },
    components: {
    AuthenticatedLayout,
    PrimaryButton,
    InputLabel
},
    data() {
        return {
            form:{
                name : '',
                price : 0,
                description : '',
                category : '',
                image : '',
                otherImages : [],
            },
            preferencesTab : ["maison et meubles", "appareils ménagers", "quincaillerie et matéreux de construction", "accessoires et pièces automobils", "cadeaux et artisanat", "fournitures pour animaux", "santé et soins personnels", "épiceries", "vêtements", "sport et divertissement", "chaussures et couvre-chefs", "maternité, bébé et jouets", "matériels informatique", "beauté et soins personnels"]
        }
    },
    mounted() {

    },

    methods:{
        takePicture(id){
            let input = document.getElementById(id);
            this.form.image = input.files[0];
        },
        takeManyPicture(id){
            let input = document.getElementById(id);
            let count;
            input.files.length > 5 ? count = 5 : count = input.files.length;
            for (let i = 0; i < count; i++) {
                this.form.otherImages[i] = input.files[i];
            }
        },
        submit(){
            // console.log(this.form);
            let form = new FormData();
            form.append('name', this.form.name);
            form.append('category', this.form.category);
            form.append('price', this.form.price);
            form.append('description', this.form.description);
            form.append('image', this.form.image);
            for (let i = 0; i < this.form.otherImages.length; i++) {
                form.append(`image${i}`, this.form.otherImages[i]);
            }

            axios.post('/article', form).then(() => {
                window.location.href='/shop'
            })
        }
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <form @submit.prevent="submit()" class="bg-[rgb(255,255,255)] p-4 border rounded-2xl h-full m-4 header mt-16">
            <legend class="text-2xl mb-4 text-center font-semibold">Créer votre article</legend>
            <div>
                <InputLabel class="my-2  block w-full  text-[#008e97] font-semibold">Nom</InputLabel>
                <input type="text" v-model="form.name" class="block border-b-[#505050] shadow-xl border-b p-2 w-full" required>
            </div>
            <div>
                <InputLabel class="my-2  block w-full  text-[#008e97] font-semibold">Prix (il pourrait être débattue)</InputLabel>
                <input type="number" v-model="form.price" min="0" class="block border-b-[#505050] shadow-xl border-b p-2 w-full" required>
            </div>
            <div>
                <InputLabel class="my-2  block w-full  text-[#008e97] font-semibold">catégorie</InputLabel>
                <select v-model="form.category" class="block border-b-[#505050] shadow-xl border-b p-2 w-full" required>
                    <option v-for="pref,i in preferencesTab" :value="pref" :key="i">{{ pref }}</option>
                </select>
            </div>
            <div>
                <InputLabel class="my-2  block w-full  text-[#008e97] font-semibold">Description</InputLabel>
                <textarea v-model="form.description" class="create block border-b-[#505050] shadow-xl border-b p-2 w-full" required></textarea>
            </div>
            <div>
                <InputLabel class="my-2  block w-full  text-[#008e97] font-semibold">Image</InputLabel>
                <input type="file" id="article" @change="takePicture('article')" class="block border-b-[#505050] shadow-xl border-b p-2 w-full" required>
            </div>
            <div>
                <InputLabel class="my-2  block w-full  text-[#008e97] font-semibold">D'autres images de l'article (faculatatif) 5 max</InputLabel>
                <input type="file" id="inputMany" @change="takeManyPicture('inputMany')" class="block border-b-[#505050] shadow-xl border-b p-2 w-full" multiple max="5">
            </div>
            <PrimaryButton>Créer</PrimaryButton>
        </form>
    </AuthenticatedLayout>
</template>