

const logInOutBtn = ()=>{

    
                form = document.createElement("form");
                form.setAttribute("action","http://localhost:3000/logout");
                form.setAttribute("method","POST");
                document.body.appendChild(form);
                form.submit();
            }
            
        
    