<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
*{

    padding: 0;
    margin: 0;
    box-sizing: border-box;
}

body{
    background-color: #05c46b;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
}

form
{

max-width: 500px;
margin: 70px auto;

background-color: #fff;


padding: 20px 30px;
}
label{
    display: block;
    font-family: inherit;
    margin:10px 0 10px 0 ;
}
   
input
{
    width: 95%;
    border: 1px solid #777;
    border-radius: 2px;
    padding: 10px;
    font-family: inherit;
   font-weight: 1px;
}
button
{
    display: block;
    width: 95%;
    margin: 20px 0 20px 0;
    background-color: #05c46b;
    padding: 10px;
    font-family: inherit;
}


.error-message
{
    color: red;
    display: none;
}
span
{
    display: none;
    font-size:small;
}

.success_response{
   
    text-align: center;
    color: #00a67;
}
    </style>
</head>


<body>
    

<form id="registerationForm" action="register.php" method="POST">

<img src="https://images.freecreatives.com/wp-content/uploads/2015/04/logo025.png" alt="Site Logo" style="width: 100%; max-height: 150px;">
    <label for="fname">First Name</label>
    <input id="fname" type="text" name="fname">
    <span class="error-message" id="fname_error">*</span>

    <label for="lname">Last Name</label>
    <input id="lname" type="text" name="lname" >
    <span class="error-message" id="lname_error">*</span>

    <label for="email">Email</label>
    <input id="email" type="text" name="email">
    <span class="error-message" id="email_already_registered">*</span>

    <label for="password">Password</label>
    <input id="password" type="password" name="password">
    <span class="error-message" id="password_error">*</span>

    <label for="cpassword">Confirm Password</label>
    <input id="cpassword" type="password" name="confirm_password">
    <span class="error-message" id="cpassword_error">*</span>

    <label for="mnumber">Mobile Number</label>
    <input id="mnumber" type="text" name="mobile">
    <span class="error-message" id="mobile_error">*</span>

    <button type="submit">Sign Up</button>
    <span class="success_response" id="success_response">*</span>

</form>
<script>
document.getElementById('registerationForm').addEventListener('submit',async function (event)
{
event.preventDefault();
const formData=new FormData(this);


let response = await fetch('register.php', {
        method: 'POST',
        body: formData
    });

let responseData= await response.json();
if(responseData.message!=="User Registered Successfully")
{
   
const errorSpan= document.querySelectorAll('.error-message');

errorSpan.forEach(element=>{ element.style.display = 'block';
            element.textContent = '';}); 

if(responseData.fname_error)
{
    document.getElementById('fname_error').textContent=responseData.fname_error;
}

if(responseData.lname_error)
{
    document.getElementById('lname_error').textContent=responseData.lname_error;
}


if(responseData.email_already_registered)
{
    document.getElementById('email_already_registered').textContent=responseData.email_already_registered;
}

if(responseData.password_error)
{
    document.getElementById('password_error').textContent=responseData.password_error;
}

///hhh
if(responseData.cpassword_error)
{
    document.getElementById('cpassword_error').textContent=responseData.cpassword_error;
}

if(responseData.mobile_error)
{
    document.getElementById('mobile_error').textContent=responseData.mobile_error;
}

}
else
{
    
    const errorSpan = document.querySelectorAll('.success_response');
                errorSpan.forEach(element => {
                    element.style.display = 'block';
                    element.textContent = '';
                });
                document.getElementById('success_response').textContent = responseData.message;
            window.location.replace("login.php");

}
}








)

</script>
</body>
</html>