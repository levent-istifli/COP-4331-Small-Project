// Handles the home sign up/sign in page


	function getLoginData() {
	   let username = document.getElementById("username").value;
	   let password = document.getElementById("password").value;

	   return {
	      login: username,
	      password: password
	   };
	}

	function getSignupData() {
	   let firstname = document.getElementById("firstname").value;
	   let lastname = document.getElementById("lastname").value;
           let username = document.getElementById("username").value;
           let password = document.getElementById("password").value;

           return {
	      firstname: firstname,
	      lastname: lastname,
              login: username,
              password: password
           };
        }


        // Handle login button click
        async function handleLogin() {
           const { login, password } = getLoginData();
	   const preJsonData = { login, password };
	   console.log(preJsonData);
	   console.log(JSON.stringify(preJsonData));
	   
	   try {
	      const response = await fetch("../LAMPAPI/login.php", {method: "POST", headers: { "Content-Type": "application/json" },
		      				   body: JSON.stringify(preJsonData) });
	
	   const result = await response.json();
	   console.log(result);

	   if(result.Error == "") {
	      sessionStorage.setItem('userId', result.ID);
	      sessionStorage.setItem('FirstName', result.FirstName);
	      sessionStorage.setItem('LastName', result.LastName);
	      window.location.href = "contact_manager.html";    
	   }
	   } catch (error) {
		   console.error("Error:", error);
	   }
            
        }

        // Handle signup button click
        async function handleSignup() {
	   const { firstname, lastname, login, password } = getSignupData();
           const preJsonData = { firstname, lastname, login, password };
           console.log(preJsonData);
           console.log(JSON.stringify(preJsonData));

           try {
              const response = await fetch("../LAMPAPI/signup.php", {method: "POST", headers: { "Content-Type": "application/json" },
                                                   body: JSON.stringify(preJsonData) });

           const result = await response.json();
           console.log(result);
           } catch (error) {
                   console.error("Error:", error);
           }
	   
	   window.location.href = "index.html";
        }

