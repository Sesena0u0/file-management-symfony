function edit__(id, name, type) {
    $('.cont-rename').slideDown();
    $('.cont-rename').empty();
    $('.cont-rename').append(`
    
        <div class="container-lg-fluid" >
            <div class="d-flex justify-content-end me-2">
                <div class="fa fa-close mt-2 text-app -close c-pointer" onclick="_hidden($('.cont-rename'))" ></div>
            </div>
            <form action="`+type+`" method="post" >
                <div class="mb-3">
                    <label class="form-label text-white" for="mdp">Name</label>
                    <input type="text" value="`+name+`" id="name" name="name" class="form-control form-control-lg" placeholder="name" require />
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <button class="btn btn-primary text-white mx-2" type="submit" onclick="_hidden($('.cont-rename'))"></button>  
                </div>
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
                            <button class="btn-primary text-white px-5" type="submit" style="border-radius: 10px;">Save</button>  
                        </div>
                    </form>
                </div>
            </div>
        </div>
    
    `);
}

function show_option(type, id, name) {
    $('.cont-option').slideDown();
    $('.cont-option').empty();
    $('.cont-option').append(`
    
    <div class="card card-control">
        <div class="d-flex flex-column">
            <div class="d-flex justify-content-end me-2">
                <div class="fa fa-close mt-2 text-app -close c-pointer"></div>
            </div>
            <div class="container">
                <a class="list_info d-flex c-pointer m-1 nav-link" href="/`+type+`/`+id+`" >
                    <div class="d-flex flex-column justify-content-center w-100">
                        <div class="d-flex justify-content-start w-100 m-1">
                            <i class="fa fa-eye text-white me-3 mt-1" ></i>
                            <div class="text-white mx-1">Open</div>
                        </div>
                    </div>
                </a>
                <div class="list_info d-flex c-pointer m-1" onclick="edit__(`+id+`, '`+name+`', '/edit_`+type+`/`+id+`' ) " >
                    <div class="d-flex flex-column justify-content-center w-100">
                        <div class="d-flex justify-content-start w-100 m-1">
                            <i class="fa fa-edit text-white me-3 mt-1" ></i>
                            <div class="text-white mx-1">Rename</div>
                        </div>
                    </div>
                </div>
                <a class="list_info d-flex c-pointer m-1 nav-link" href="/delete_`+type+`/`+id+`" >
                    <div class="d-flex flex-column justify-content-center w-100">
                        <div class="d-flex justify-content-start w-100 m-1">
                            <i class="fa fa-trash text-white me-3 mt-1" ></i>
                            <div class="text-white mx-1">Delete</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    `);
}

function _hidden(self) {
    $(self).slideUp();
} 

function switch_enfant(self, other) {
    $(other).slideToggle();
    
}
