<template>
    <section class="container">
        <div class="row mt-5">
            <add-car @new-car-added="getCars"></add-car>
        </div>

        <div class="row mt-5">
            <table class="table">
                <thead>
                    <th>ID</th>
                    <th>Марка</th>
                    <th>Модель</th>
                    <th>Цвет</th>
                    <th>Номер</th>
                    <th>Паркинг оплачен</th>
                    <th>Комментарий</th>
                </thead>
                <tbody>
                    <tr v-if="error">
                        <td colspan="7" class="text-center">
                            Упс... не удалось загрузить список автомобилей:(<br>
                            Попробуйте обновить страницу?
                        </td>
                    </tr>
                    <tr v-for="car in cars" :key="car.id">
                        <td>{{ car.id }}</td>
                        <td>{{ car.marque }}</td>
                        <td>{{ car.model }}</td>
                        <td>{{ car.color.name }}</td>
                        <td>{{ car.number }}</td>
                        <td>{{ car.parking_paid ? 'да' : 'нет' }}</td>
                        <td>{{ car.comment }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>

<script>
import axios from 'axios'
import AddCar from './AddCar.vue'

export default {
    name: 'CarsList',
    components: {
        AddCar,
    },
    data() {
        return {
            cars: [],
            error: false
        }
    },
    methods: {
        getCars() {
            axios.get('/api/cars')
                .then((response) => {
                    this.cars = response.data
                    this.error = false
                })
                .catch((error) => {
                    this.error = true
                })
        },
    },
    created() {
        this.getCars()
    }
}
</script>

<style lang="scss" scoped>
table tbody tr:nth-child(2n+1) {
    background: #eee;
}
</style>