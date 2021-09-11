<template>
    <form @submit.prevent="addCar()" class="container">
        <div v-if="error" class="text-danger">
            {{ error }}
        </div>
        <div v-if="stored" class="text-success">
            Машина добавлена
        </div>
        <div class="row">
            <div class="col-4">
                <input v-model="marque" type="text" class="form-control mb-2" placeholder="Марка" @focus="dropMessages" />
                <input v-model="model" type="text" class="form-control mb-2" placeholder="Модель" @focus="dropMessages" />
                <input v-model="number" type="text" class="form-control mb-2" placeholder="Номер" @focus="dropMessages" />
                <select v-model="color_id" class="form-control mb-2" @focus="dropMessages">
                    <option 
                        v-for="color in colors" 
                        :key="color.id"
                        :value="color.id">
                        {{ color.name }}
                    </option>
                </select>
            </div>
            <div class="col-8">
                <textarea v-model="comment" rows="3" class="form-control" placeholder="Комментарий" @focus="dropMessages"></textarea>
                <div class="ml-4 my-3">
                    <input v-model="parking_paid" id="parking_paid" type="checkbox" class="form-check-input" @focus="dropMessages" />
                    <label for="parking_paid" class="form-check-label">Паркинг оплачен</label>
                </div>
                <button type="submit" class="btn btn-medium btn-success">
                    Сохранить
                </button>
                <button @click="dropData" type="button"
                    class="btn btn-medium btn-secondary ml-2">
                    Сбросить
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import axios from 'axios'

export default {
    name: 'AddCar',
    data() {
        return {
            error: '',
            stored: false,

            colors: [],

            marque: '',
            model: '',
            color_id: 1,
            number: '',
            parking_paid: false,
            comment: ''
        }
    },
    methods: {
        dropMessages() {
            this.error = ''
            this.stored = false
        },
        dropData() {
            this.marque = ''
            this.model = ''
            this.color_id = 1
            this.number = ''
            this.parking_paid = false
            this.comment = ''
            this.dropMessages()
        },
        getColors() {
            axios.get('/api/colors')
            .then((response) => {
                this.colors = response.data
            })
            .catch((error) => {
                this.error = 'Не удалось загрузить цвета - попробуйте обновить страницу'
            })
        },
        addCar() {
            console.log({
                marque: this.marque,
                model: this.model,
                color_id: this.color_id,
                number: this.number,
                parking_paid: this.parking_paid,
                comment: this.comment
            })
            axios.post('/api/cars', {
                    marque: this.marque,
                    model: this.model,
                    color_id: this.color_id,
                    number: this.number,
                    parking_paid: this.parking_paid,
                    comment: this.comment
                })
                .then((response)=>{
                    console.log(response)
                    if (response.data.stored === 'ok') {
                        this.stored = true
                        this.error = ''
                        this.$emit('new-car-added')
                    }
                    else if (response.data.stored === 'no') {
                        this.stored = false
                        this.error = response.data.error
                    }
                    else this.error = "Что-то пошло не так, попробуйте ещё раз"
                })
                .catch((error) => {
                    console.log(error)
                    this.error = error
                })
        }
    },
    created() {
        this.getColors()
    }
}
</script>