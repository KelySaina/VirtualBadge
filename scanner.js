var scan = new Vue({
    el: '#scan',
    data: {
        //server: 'virtualbadge',
        //port: '3333',
        errors: [],
        matricule:null,
        parcours: null,
        complete: false,
        incomplete: false,
        warn: false,
        reset: false,
        alerte: false,

        mat: null,
        name: null,
        surname: null,
        par: null,
        m: null
    },
    methods:{
        checkForm: function (e) {
            if (this.matricule && this.parcours) {
              return true;
            }
      
            this.errors = [];
      
            if (!this.matricule) {
              this.errors.push('Matricule');
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
        showLog(){
            if(this.checkForm()){
                var d = {
                    mat : scan.matricule,
                    par : scan.parcours
                }
                var f = this.toFormData(d);
                axios.post('http://virtualbadge:3333/handle.php?action=verifUser',f)
                .then((response)=>{
                    if(response.data.status == 'success'){
                        scan.mat = " "+response.data.matricule
                        scan.name = " "+response.data.nom
                        scan.surname = " "+response.data.prenom
                        scan.par = " "+response.data.parcours
                        scan.m = " "+response.data.message

                        scan.complete = true
                    }
                    else if(response.data.status == 'warning'){
                        scan.m = response.data.message
                        scan.warn = true
                    }
                    else{
                        scan.m = response.data.message
                        scan.incomplete = true
                    }
                })
            }
        },
        notIn(){
            axios.post('http://virtualbadge:3333/handle.php?action=notIn')
            .then((response)=>{
                
                if(response.data.status == 's'){
                    scan.m = response.data.message
                    scan.reset = true
                }
                else{
                    scan.m = response.data.message
                    scan.incomplete = true
                }
            }) 
        }
    }
})