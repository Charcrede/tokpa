<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Icon from "@/Icons/Icon.vue";
export default {
    props: {
        article: Object,
        image: Object,
        proprio : Boolean,
        images : Array,

    },
    components: {
        AuthenticatedLayout,
        Icon,
    },
    data() {
        return {
            dotTable : [],
            current : 0,
            intervalId : '',
            form : {
                name : '',
                description : '',
                price : '',
                category : '',
                image : '',
                images : [],
            },
            preview : [],
            previews : [],
            modifier : false,
            categories : ["maison et meubles", "appareils ménagers", "quincaillerie et matéreux de construction", "accessoires et pièces automobils", "cadeaux et artisanat", "fournitures pour animaux", "santé et soins personnels", "épiceries", "vêtements", "sport et divertissement", "chaussures et couvre-chefs", "maternité, bébé et jouets", "matériels informatique", "beauté et soins personnels"],
        }
    },
    mounted() {
        for (let i = 0; i < this.images.length + 1; i++) {
            this.dotTable[i] = i;
        }
        clearInterval(this.intervalId);
        this.intervalId = setInterval(() => {
            this.current < this.images.length ? this.current++ : this.current = 0
            let div = document.getElementById('showArticles');
            if(div){
                div.scrollLeft = div.offsetWidth * this.current
            }else{
                clearInterval(this.intervalId);
            }
        }, 4000);
    },
    methods: {
        getPath(name) {
            return new URL("../../../../public/article_medias/" + name, import.meta.url).href
        },
        showModif(){
            this.form.name = this.article.name;
            this.form.price = this.article.price;
            this.form.description = this.article.description;
            this.form.category = this.article.category;
            this.preview[0] = this.getPath(this.image.path);
            for (let i = 0; i < this.images.length; i++) {
                this.previews[i] = this.getPath(this.images[i].path);
            }
            this.modifier = true;
        },
        getPrincipalImage(fileHolder, preview) {
            let input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*'

            input.onchange = function (e) {
                let file = e.target.files[0]; // Nouvelle variable locale file pour stocker le fichier
                let reader = new FileReader();

                reader.onload = function () {
                    let content = reader.result;
                    // console.log("Contenu du fichier:", content); // Vous pouvez faire ce que vous voulez avec le contenu du fichier ici
                    preview[0] = content; // Assurez-vous que preview est un tableau valide et que vous pouvez accéder à son premier élément
                    fileHolder.image = file; // Modifier la référence à l'objet file d'origine
                };
                reader.readAsDataURL(file);
            };
            input.click();
        },
        getImage(fileHolder, preview, i) {
            let input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*'

            input.onchange = function (e) {
                let file = e.target.files[0]; // Nouvelle variable locale file pour stocker le fichier
                let reader = new FileReader();

                reader.onload = function () {
                    let content = reader.result;
                    // console.log("Contenu du fichier:", content); // Vous pouvez faire ce que vous voulez avec le contenu du fichier ici
                    preview[i] = content; // Assurez-vous que preview est un tableau valide et que vous pouvez accéder à son premier élément
                    fileHolder.images[i] = file; // Modifier la référence à l'objet file d'origine
                };
                reader.readAsDataURL(file);
            };
            input.click();
        },
        update() {
            let newForm = new FormData();
            newForm.append('name',this.form.name);
            newForm.append('category',this.form.category);
            newForm.append('id',this.article.id);
            newForm.append('images',this.article.images);
            newForm.append('price',this.form.price);
            newForm.append('description',this.form.description);
            newForm.append('image', this.form.image);
            for (let i = 0; i < this.form.images.length; i++) {
                newForm.append(`image${i}`, this.form.images[i]);
            }
            axios.post('/article/update', newForm).then((resp) => {
                window.location.reload();
            })
        },
        closeModif(){
            this.form.name = ''
            this.form.price = ''
            this.form.category = ''
            this.form.description = ''
            this.form.image = ''
            this.form.images = []
            this.modifier = false;
        },
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="relative min-h-full">
            <form v-if="modifier" class="bg-blackTrans absolute top-[-1.8rem] left-0 right-0 z-20 p-4" @submit.prevent="update()">
                <button class="absolute top-0 right-0 z-20" @click.prevent="closeModif()">
                    <Icon name="close" width="50" height="50" fill="white"></Icon>
                </button>
                <div class="flex justify-center relative text-white">
                    <img id="logo" class="h-52 w-52  object-cover border-2 border-primary rounded-full p-[0.12rem]"
                        :src="preview[0]" alt="">
                    <button @click.prevent="getPrincipalImage(form, preview)"
                        class="absolute bottom-0 right-[20%] lg:right-[44%] bg-whiteTrans w-12 h-12 flex items-center justify-center rounded-full">
                        <Icon :name="'pencil'" width="30" height="30"></Icon>
                    </button>
                </div>
                <p class="font-semibold text-white text-xl mt-4">images secondaires :</p>
                <div class="flex justify-evenly my-4">
                    <div v-for="img,i in previews" :key="i" class="flex justify-center relative text-white">
                        <img class=" h-16 w-16  object-cover border-2 border-primary rounded-full p-[0.12rem]"
                        :src="img" alt="">
                        <button @click.prevent="getImage(form, previews, i)"
                        class="absolute bottom-0 right-0 bg-whiteTrans w-8 h-8 flex items-center justify-center rounded-full">
                        <Icon :name="'pencil'" width="20" height="20"></Icon>
                    </button>
                </div>
                </div>
                <div>
                    <label class="text-white font-bold text-xl">name :</label>
                    <input class="text-white block w-full p-4 text-xl" type="text" v-model="form.name">
                    <label class="text-white font-bold text-xl">description :</label>
                    <input class="text-white block w-full p-4 text-xl" type="text" v-model="form.description">
                    <label class="text-white font-bold text-xl">price :</label>
                    <input class="text-white block w-full p-4 text-xl" type="number" v-model="form.price">
                    <label class="text-white font-bold text-xl">category :</label>
                    <select class="text-white bg-blackTrans block w-full p-4 text-xl" v-model="form.category">
                        <option v-for="cat,i in categories" :key="i" :value="cat">{{ cat }}</option>
                    </select>
                </div>
                <button class="px-4 py-2 text-white border-white border w-full rounded-xl mt-3">Enrégistrer</button>
            </form>
            <article class="bg-white border border-primary p-2 rounded-3xl m-4 lg:w-1/3 lg:m-auto">
                <div class="relative flex flex-col items-center">
                <div class="flex overflow-scroll max-h-[500px] scroll-smooth" id="showArticles">
                    <img :src="getPath(image.path)" alt="" class="rounded-3xl w-full">
                    <img v-for="img, i in images" :key="i" :src="getPath(img.path)" alt="" class="rounded-3xl">
                    <span v-if="proprio" class="absolute top-0 right-0 font-bold px-2 h-8 flex justify-center items-center mr-2 mt-2 rounded-full bg-white">commandes : {{ article.commande }}</span>
                </div>
                <div class="flex gap-1 relative bottom-4">
                    <span v-for="dot in dotTable" :class="dot == current ? 'bg-blackTrans' : 'bg-whiteTrans'" class="w-2 h-2 block rounded-full"></span>
                </div>
            </div>
            <p class="font-bold text-2xl font-economica text-center w-fit mx-auto border-b border-b-primary text-primary px-2 m-2">{{ article.name }}</p>
            <p class="font-semibold m-2">{{article.description}}</p>
            <div class="flex justify-between m-2">
                <span class="text-[#505050]">{{ article.category }}</span>
                <span class="text-primary">{{ article.price }} F</span>
                <button @click="showModif()"><Icon :name="'pencil'" width="28" height="28" fill="#7e4a7c"></Icon></button>
            </div>
        </article>
    </div>
    </AuthenticatedLayout>
</template>