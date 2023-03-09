var app = new Vue({
    el: "#app",
    data: {
        server: '192.168.43.209',
        errors: [],
        matricule:null,
        nom: null,
        prenom: null,
        parcours: null,
        complete: false,
        m: null,
        mErr: false,
    },
    methods:{
        checkForm: function (e) {
            if (this.matricule && this.nom && this.parcours) {
              return true;
            }
      
            this.errors = [];
      
            if (!this.matricule) {
              this.errors.push('Matricule');
            }
            if (!this.nom) {
              this.errors.push('Nom');
            }
            if (!this.parcours) {
                this.errors.push('Parcours');
            }
        },
        toFormData(obj){
            var fd = new FormData();
            for(var i in obj){
                fd.append(i,obj[i]);
            }
            return fd;
        },
        sendLog(){
            if(this.checkForm()){
                var o = {
                    matricule : this.matricule,
                    nom : this.nom,
                    prenom : this.prenom,
                    parcours : this.parcours
                }
                var f = this.toFormData(o)

                axios.post('http://'+this.server+':1060/handle.php?action=regUser',f)
                .then((response)=>{
                    if(response.data.status == 'success' ){
                        app.complete = true;

                        new QRCode(document.getElementById("qrcode"), {
                            text: response.data.matricule+"-"+response.data.parcours,
                            width: 150,
                            height: 150,
                            colorDark : "#000000",
                            colorLight : "#ffffff",
                            correctLevel : QRCode.CorrectLevel.H
                        })
                        
                    }
                    else{
                        app.m = response.data.message
                        app.mErr = true
                    }
                })

                this.matricule = this.nom = this.prenom = this.parcours = null
                this.errors = []
            }
            
        },

    },


})

