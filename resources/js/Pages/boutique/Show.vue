<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Link } from "@inertiajs/vue3";
import Icon from "@/Icons/Icon.vue"
import axios from "axios";
import { ref } from "vue";

export default {
    props: {
        boutique: Array | String,
        logo: Array | undefined,
        banner: Array | undefined,
        propsArticles: Array | undefined,
        articlesCount: Number,
    },
    components: {
        AuthenticatedLayout,
        PrimaryLink,
        PrimaryButton,
        Icon,
        Link,
    },
    data() {
        return {
            pathToMedia: "../../../../public/boutique_medias/",
            // pathToMedia : 'C:/Users/Lonely wild/Desktop/tokpa/public/boutique_medias',
            openDesc: false,
            path: '',
            display: 'plusRecent',
            num: 9,
            articles: [],
            div: '',
            form: {
                logo: '',
                name: '',
                site: '',
                telephone: '',
                email: '',
                description: '',
                address : ''
            },
            image: [],
            modifier: false,
            categories : ["maison et meubles", "appareils ménagers", "quincaillerie et matéreux de construction", "accessoires et pièces automobils", "cadeaux et artisanat", "fournitures pour animaux", "santé et soins personnels", "épiceries", "vêtements", "sport et divertissement", "chaussures et couvre-chefs", "maternité, bébé et jouets", "matériels informatique", "beauté et soins personnels"],
        }
    },
    mounted() {
        if(this.boutique){

            this.articles = this.propsArticles;
            this.div = document.querySelector('.grid');
        this.image[0] = this.getPath('boutique', this.logo[0].path);
        this.form.name = this.boutique.name;
        this.form.category = this.boutique.category;
        this.form.telephone = this.boutique.telephone;
        this.form.email = this.boutique.email;
        this.form.description = this.boutique.description;
        this.form.site = this.boutique.site;
        this.form.address = this.boutique.address;
    }
    },
    methods: {
        getPath(type, name) {
            if (type === 'boutique') {
                return new URL("../../../../public/boutique_medias/" + name, import.meta.url).href;
            } else if (type === 'article') {
                return new URL("../../../../public/article_medias/" + name, import.meta.url).href
            }
        },
        changeDisplay(display, num) {
            let div = document.querySelector('.grid');
            axios.post(`/${display}/`, {
                'num': this.num,
                'boutique': this.boutique
            }).then((resp) => {
                this.articles = resp.data;
                this.display = display;
                setTimeout(() => {
                    div.scrollTop = 0;
                }, 100);
            })
        },
        showMore() {
            this.num += 9;
            let div = document.querySelector('.grid');
            axios.post(`/plusRecent`, {
                'num': this.num,
                'boutique': this.boutique
            }).then((resp) => {
                this.articles = resp.data
                setTimeout(() => {
                    div.scrollTop = div.scrollHeight
                }, 100);
            })
        },
        getImage(fileHolder, preview) {
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
                    fileHolder.logo = file; // Modifier la référence à l'objet file d'origine
                };
                reader.readAsDataURL(file);
            };
            input.click();
        },
        update(form) {
            let newForm = new FormData();
            newForm.append('name',form.name);
            newForm.append('id',this.boutique.id);
            newForm.append('address',form.address);
            newForm.append('telephone',form.telephone);
            newForm.append('category',form.category);
            newForm.append('email',form.email);
            newForm.append('site',form.site);
            newForm.append('logo',form.logo);
            newForm.append('description',form.description);
            axios.post('shop/update', newForm).then((resp) => {
                window.location.reload();
            })
        }

    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div v-if="!boutique" class="p-4 bg-white rounded-2xl mx-4 my-12 lg:w-1/3 lg:mx-auto">
            <p class="text-xl mb-4">Créer votre boutique et commencer à exposer vos produits</p>
            <img src="../../../../storage/app/assets/image/shopping-cart.png" alt="">
            <PrimaryLink :href="'/shop/create'">Créer ma boutique</PrimaryLink>
        </div>
        <div class="relative" v-else>
            <form v-if="modifier" class="bg-blackTrans absolute top-[-1rem] bottom-[-1rem] right-0 left-0 z-10 p-4 pt-2"
                @submit.prevent="update(form)">
                <button class="absolute top-0 right-0 z-20" @click.prevent="modifier = false">
                    <Icon name="close" width="50" height="50" fill="white"></Icon>
                </button>
                <div class="flex justify-center relative">
                    <img id="logo" class="h-52 w-52  object-cover border-2 border-primary rounded-full p-[0.12rem]"
                        :src="image[0]" :alt="pathToMedia + logo[0].path">
                    <button @click.prevent="getImage(form, image)"
                        class="absolute bottom-0 right-[20%] lg:right-[45 %] bg-whiteTrans w-12 h-12 flex items-center justify-center rounded-full">
                        <Icon :name="'pencil'" width="30" height="30"></Icon>
                    </button>
                </div>
                <div>
                    <label class="text-white font-bold text-xl">name :</label>
                    <input class="text-white block w-full p-4 text-xl" type="text" v-model="form.name">
                    <label class="text-white font-bold text-xl">description :</label>
                    <input class="text-white block w-full p-4 text-xl" type="text" v-model="form.description">
                    <label class="text-white font-bold text-xl">telephone :</label>
                    <input class="text-white block w-full p-4 text-xl" type="number" v-model="form.telephone">
                    <label class="text-white font-bold text-xl">email :</label>
                    <input class="text-white block w-full p-4 text-xl" type="email" v-model="form.email">
                    <label class="text-white font-bold text-xl">site :</label>
                    <input class="text-white block w-full p-4 text-xl" type="text" v-model="form.site">
                    <label class="text-white font-bold text-xl">address :</label>
                    <input class="text-white block w-full p-4 text-xl" type="text" v-model="form.address">
                    <select class="text-white bg-blackTrans block w-full p-4 text-xl" v-model="form.category">
                        <option v-for="cat,i in categories" :key="i" :value="cat">{{ cat }}</option>
                    </select>
                </div>
                <button class="px-4 py-2 text-white border-white border w-full rounded-xl mt-3">Enrégistrer</button>
            </form>
            <div class="flex justify-evenly px-4 pt-4 gap-4 items-center lg:flex-col">
                <div class="w-20 h-20 overflow-hidden rounded-[50%] flex justify-center items-center relative lg:w-60 lg:h-60">
                    <img v-if="logo.length" id="logo"
                        class="h-full object-cover border-2 border-primary rounded-full p-[0.12rem] w-full"
                        :src="getPath('boutique', logo[0].path)" :alt="pathToMedia + logo[0].path">
                    <div v-else
                        class="absolute top-0 bottom-0 left-0 right-0 border-4 border-[#aaa] rounded-[50%] flex justify-center items-center overflow-hidden">
                        <Icon name="shop" width="80" height="80" fill="#aaa"></Icon>
                    </div>
                </div>
                
                <div class="flex gap-4 mr-4 lg:justify-evenly lg:w-full">
                    <div class="flex flex-col items-center text-center">
                        <span class="font-bold">{{ articlesCount }}</span>
                        <span class="text-[#888]">articles</span>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <span class="font-bold">{{ boutique.visit }}</span>
                        <span class="w-16 text-[#888]">visites</span>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <span class="font-bold">{{ boutique.commande }}</span>
                        <span class="w-16 text-[#888]">commandes</span>
                    </div>

                </div>
            </div>
            <p class="text-xl font-semibold ml-4 mt-2 lg:text-center">{{ this.boutique.name }}</p>
            <div>
                <div class="flex flex-col mt-4 ml-2 lg:flex-row lg:justify-evenly lg:mt-8">
                    <span class="text-primary flex items-center gap-2">
                        <Icon :name="'phone'" width="16" height="16" fill="#7e4a7c"></Icon>{{ boutique.telephone }}
                    </span>
                    <span class="text-primary flex items-center gap-2">
                        <Icon :name="'address'" width="16" height="16" fill="#7e4a7c"></Icon>{{ boutique.address }}
                    </span>
                    <span v-if="boutique.email" class="text-primary flex items-center gap-2">
                        <Icon :name="'mail'" width="16" height="16" fill="#7e4a7c"></Icon>{{ boutique.email }}
                    </span>
                    <span v-if="boutique.site" class="text-primary flex items-center gap-2">
                        <Icon :name="'mail'" width="16" height="16" fill="#7e4a7c"></Icon>{{ boutique.site }}
                    </span>
                </div>
                <div class="m-2 duration-300 overflow-hidden" :class="openDesc ? 'max-h-auto' : 'max-h-12'">
                    <p @click="openDesc = !openDesc">{{ boutique.description }}</p>
                    <!-- <button class="float-right mt-[-22px] mr-2" @click="openDesc = true">Voir plus</button> -->
                </div>
                <div class="flex gap-2 mt-4 px-4">
                    <Link href="/article/create" class="w-1/2 bg-primary text-center px-2 py-1 rounded-[8px] text-white">
                    Créer un article</Link>
                    <button @click="modifier = true"
                        class="w-1/2 border text-center border-primary px-2 py-1 rounded-[8px] text-primary">modifier
                    </button>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-center mb-4">
                    <button @click="changeDisplay('plusRecent', num)" class="text-xl mr-2"
                        :class="display === 'plusRecent' ? 'font-semibold' : ''">Les plus récents</button> <span
                        class="font-semibold text-xl">|</span> <button @click="changeDisplay('tendance', num)"
                        class="ml-2 text-xl" :class="display === 'tendance' ? 'font-semibold' : ''">Les plus
                        demandés</button>
                </div>
                <div v-if="articles.length > 0">
                    <div class="gap-1 mx-1 grid grid-flow-dense grid-cols-3 overflow-scroll h-[490px] lg:h-auto lg:grid-cols-auto ">

                        <div v-for="art, i in articles" :key="i" class="h-40 overflow-hidden relative lg:h-auto">
                            <div class="w-full h-full overflow-hidden">
                                <img class="h-full object-cover" :src="getPath('article', art.article_media.path)" alt="">
                            </div>
                            <span
                                class="absolute top-0 right-0 m-1 rounded-full h-8 bg-white min-w-8 flex justify-center text-primary items-center font-bold text-xl"
                                v-if="display === 'tendance'"> {{ art.commande }}</span>
                            <Link class="absolute top-0 left-0 right-0 bottom-0 opacity-0"
                                :href="route('article.show', art)">''</Link>
                        </div>
                    </div>
                    <PrimaryButton @click="showMore()">Voir plus</PrimaryButton>
                </div>
                <div v-else class="justify-center items-center text-xl p-4 mt-8 h-[490px]">
                    Aucun article pour le moment, créer votre premier article et commencer par vendre
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>