<template>
    <div class="upload" v-if="!onlyShow">
        <div class="find-file-wrap">
            <label :for="id" class="find-file">
                {{title}}
                <input type="file" :id="id" @change="changeFile" :accept="accept" multiple v-if="multiple">
                <input type="file" :id="id" @change="changeFile" :accept="accept" v-else>
            </label>

            <p class="comment">{{ comment }}</p>
        </div>

        <ul class="upload-list col-group" v-if="files.length > 0">
            <li v-for="(file, index) in files" :key="index">
                <div class="text-box">
                    {{ file.name }}
                </div>
                <button type="button" class="del" @click="remove(index)">
                    <i class="xi-close"></i>
                </button>
            </li>
        </ul>
    </div>
    <div class="upload" v-else>
        <ul class="upload-list col-group" v-if="files.length > 0">
            <li v-for="(file, index) in files" :key="index">
                <a :href="file.url">
                    <div class="text-box">
                        {{ file.name }}
                    </div>
                </a>
            </li>
        </ul>
    </div>
</template>
<style>
.upload {
    width:100%;
}
.upload .m-ratioBox-wrap {
    max-width:200px; padding-top:200px;
}
.upload input {
    display:none;
}
.find-file {
    display: flex; flex:0 0 auto;
    justify-content: center;
    align-items: center;
    min-width: 120px;
    height: 40px;
    padding:0 20px;
    border-radius: 20px;
    background-color: #7542dd;
    font-weight: 500;
    color: #fff;
    white-space: nowrap;
    cursor:pointer;
}
.find-file-wrap {
    display:flex; align-items: center;
}
.find-file-wrap .comment {
    width: calc(100% - 120px);
    padding-left: 16px;
    font-size: 14px;
    color: #707070;
}
.upload-list {
    width: 100%; min-height: 48px; margin-top:8px;
    flex-flow: wrap; flex:auto;
    padding: 8px; gap: 8px;
    background: #f5f5f5; border-radius: 16px;
}
.upload-list li {
    display:flex; justify-content: space-between; align-items: center;
    padding: 4px 16px; padding-right: 8px;
    font-size: 14px; align-items: center;
    background: #fff; border-radius: 12px;
}
.upload-list li:last-of-type {
    margin-bottom:0;
}
.upload-list li button {
    margin-left:12px;
    font-size: 14px; font-weight: bold;
}

</style>
<script>
export default {
    props: {
        id: {
            default : "file"
        },
        accept: {
            default: "*"
        },
        default: {
            default() {
                return []
            }
        },
        required: {
            default: true
        },
        title: {
            required: false,
            default: "파일 선택",
        },
        multiple: {
            default: false
        },
        onlyShow: {
            default: false,
        },

        comment: {
            default: ""
        },
        max: {
            default: ""
        }
    },

    data(){
        return {
            files: this.default,
        }
    },

    methods: {
        changeFile(event) {
            if(this.max && this.max < Array.from(event.target.files).length)
                return alert(`최대 ${this.max}개의 파일만 업로드할 수 있습니다.`);

            this.files = Array.from(event.target.files).map(file => {
                return {
                    name: file.name,
                    file: file,
                };
            });

            this.$emit("change", this.files.map(file => file.file));
        },

        remove(index){
            this.files = this.files.filter((img, indexData) => indexData != index);

            this.$emit("change", this.files.map(file => file.file));
        }
    },

}
</script>
