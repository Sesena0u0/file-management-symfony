function edit__(id, name, type) {
    $('.info_file').empty();
    $('.info_file').append(`
    
        <div class="container-lg-fluid mt-5" >
            
            <form action="`+type+`" method="post" >
                <div class="form-floating mb-3">
                    <input type="text" value="`+name+`" id="name" name="name" class="form-control form-control-lg" placeholder="name" require />
                    <label class="form-label" for="mdp">Name</label>
                </div>
                <button class="btn-primary text-white" type="submit" style="border-radius: 10px;">Enregistrer</button>  
            </form>

        </div>

    `);
}

function showInfoperso__(path, name, email) {
    $('.info_content_user').empty();
    $('.info_content_user').append(`
    
        <div class="d-flex w-100">
            <div class="d-flex flex-column m-2 w-100">
                <div class="text-white h2">
                    Personal Information
                </div>
                <div class="text-white m-1 w-100">
                    <form action="`+path+`" method="post" class="d-flex flex-column justify-content-between w-100" style="height: 70vh">
                        <div class="cardform">
                            <div class="form-floating mb-3">
                                <input type="text" value="`+email+`" id="email" name="email" class="form-control form-control-lg" placeholder="email" require />
                                <label class="form-label" for="email">Email</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" value="`+name+`" id="name" name="name" class="form-control form-control-lg" placeholder="name" require />
                                <label class="form-label" for="name">Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="password" require />
                                <label class="form-label" for="password">New Password</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="form-floating mx-2">
                                <input type="password" id="lpassword" name="lpassword" class="form-control form-control-lg" placeholder="last password" require />
                                <label class="form-label" for="lpassword">Last Password</label>
                            </div>
                            <button class="btn-primary text-white" type="submit" style="border-radius: 10px;">Save</button>  
                        </div>
                    </form>
                </div>
            </div>
        </div>
    
    `);
}