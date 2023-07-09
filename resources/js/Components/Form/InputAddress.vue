<template>
    <div class="m-input-wrap type01">
        <div class="m-input-withBtn type02">
            <div class="m-input m-input-text type01" style="width:100%;">
                <input type="text" placeholder="주소" v-model="form[address]" :name="address" @change="change" disabled style="width:100%; text-align: left;">
            </div>

            <button type="button" class="m-input-btn" id="find_address">주소찾기</button>
        </div>

        <div class="m-input-text type01">
            <input type="text" placeholder="상세주소" v-model="form[address_detail]" :name="address_detail" id="address_detail" style="margin-top:10px;" @change="change" v-if="!form[address]" disabled>
            <input type="text" placeholder="상세주소" v-model="form[address_detail]" :name="address_detail" id="address_detail" style="margin-top:10px;" @change="change" v-else>
        </div>

        <div class="m-input-text type01">
            <input type="text" disabled placeholder="우편번호" v-model="form[address_zipcode]" :name="address_zipcode" id="address_zipcode" style="width:150px; margin-top:10px;" @change="change">
        </div>


        <p class="m-input-error" v-if="form.errors[address]">{{form.errors[address]}}</p>
        <p class="m-input-error" v-if="form.errors[address_detail]">{{form.errors[address_detail]}}</p>
        <p class="m-input-error" v-if="form.errors[address_zipcode]">{{form.errors[address_zipcode]}}</p>
    </div>
</template>
<script>


export default {
    props: {
        form: {
            default : {
                errors: {}
            }
        },
        address: {
            default: "address"
        },
        address_detail: {
            default: "address_detail"
        },
        address_zipcode: {
            default: "address_zipcode"
        },
        activated: false,
    },
    data(){
        return {

        }
    },

    methods: {
        change(e){
            e.preventDefault();

            this.emit(e.target.name, e.target.value);
        },

        emit(name, value){
            this.$emit("change", {
                name: name,
                value: value
            });
        }
    },

    mounted() {
        let self = this;

        document.getElementById("find_address").addEventListener("click", function(){ //주소입력칸을 클릭하면
            //카카오 지도 발생
            new daum.Postcode({
                oncomplete: function(data) { //선택시 입력값 세팅
                    document.getElementById("address_detail").focus(); // 주소 넣기

                    self.emit(self.address, data.address);
                    self.emit(self.address_zipcode, data.zonecode);
                }
            }).open();
        });
    }
}
</script>
