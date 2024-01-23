function edit__(id, name, type) {
    $('.info_file').empty();
    $('.info_file').append(`
    
        <div class="container-fluid mt-2" >
            
            <form action="`+type+`/`+id+`" methode="post" >
                <div class="form-floating mb-3">
                    <input type="text" value="`+name+`" id="`+name+`" name="_`+name+`" class="form-control form-control-lg" placeholder="name" />
                    <label class="form-label" for="mdp">Name</label>
                </div>
                <button class="btn-primary text-white" type="submit" style="border-radius: 10px;">Enregistrer</button>  
            </form>

        </div>

    `);
}