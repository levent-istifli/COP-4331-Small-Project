// Handles the add, search, delete, edit functions


	//Add the user information to global storage for current session
	const userid = sessionStorage.getItem('userId');
        const FIRSTNAME = sessionStorage.getItem('FirstName');
        const LASTNAME = sessionStorage.getItem('LastName');

	const greetingString = "Welcome " + FIRSTNAME;
	document.getElementById("greeting").innerHTML = greetingString;


	function getContactData() {
	   let firstname = document.getElementById("addfirstname").value;
	   let lastname = document.getElementById("addlastname").value;
           let email = document.getElementById("addemail").value;
           let phonenumber = document.getElementById("addphonenumber").value;

           return {
	      firstname: firstname,
	      lastname: lastname,
              phonenumber: phonenumber,
              email: email
           };
        } 


	function getSearchData() {
           let firstname = document.getElementById("sfirstname").value;
           let lastname = document.getElementById("searchlastname").value;
           let email = document.getElementById("searchemail").value;
           let phonenumber = document.getElementById("searchphonenumber").value;

           return {
              FirstName: firstname,
              LastName: lastname,
              PhoneNumber: phonenumber,
              Email: email
           };
        }


	async function handleAddContact() {
		const { firstname, lastname, phonenumber, email } = getContactData();
           	const preJsonData = { userid,firstname, lastname, phonenumber, email };
           	console.log(preJsonData);
           	console.log(JSON.stringify(preJsonData));

		try {
              const response = await fetch("../LAMPAPI/add_contact.php", {method: "POST", headers: { "Content-Type": "application/json" },
                                                   body: JSON.stringify(preJsonData) });

           const result = await response.json();
           console.log(result);
           } catch (error) {
                   console.error("Error:", error);
           }

		document.getElementById("searchinfo").style.display = "block";
                document.getElementById("contactinput").style.display = "none";

		handleSearch();
	}

	async function handleAddForm() {
		document.getElementById("searchinfo").style.display = "none";
		document.getElementById("contactinput").style.display = "block";
	}

	async function returnToSearch() {
                document.getElementById("searchinfo").style.display = "block";
                document.getElementById("contactinput").style.display = "none";
		document.getElementById("contactedit").style.display = "none";
        }

	async function handleEdit(contact) {

		//document.getElementById("searchinfo").style.display = "none";
		document.getElementById("contactedit").style.display = "block";

		document.getElementById("editfirstname").value = contact.FirstName;
		document.getElementById("editlastname").value = contact.LastName;
		document.getElementById("editemail").value = contact.Email;
		document.getElementById("editphonenumber").value = contact.PhoneNumber;

		const button = document.getElementById("confirmBtn");
		// Add a click event listener
		button.addEventListener("click", () => {
    		confirmEdit(contact);  
		});
	}

	async function confirmEdit(contact) {
		let firstname = document.getElementById("editfirstname").value;
                let lastname = document.getElementById("editlastname").value;
                let email = document.getElementById("editemail").value;
                let phonenumber = document.getElementById("editphonenumber").value;
		let id = String(contact.ID);

                const preJsonData = { userid, firstname, lastname, phonenumber, email, id };
                console.log(preJsonData);
                console.log(JSON.stringify(preJsonData));

                try {
              		const response = await fetch("../LAMPAPI/update_contact.php", {method: "POST", headers: { "Content-Type": "application/json" },
                                                   body: JSON.stringify(preJsonData) });

           	const result = await response.json();
           	console.log(result);
           	} catch (error) {
                   console.error("Error:", error);
           	}

		handleSearch();

	}

	async function handleDelete(contact) {
		let id = contact.ID;
	
		const preJsonData = { userid,  id };
                console.log(preJsonData);
                console.log(JSON.stringify(preJsonData));

                try {
                        const response = await fetch("../LAMPAPI/delete_contact.php", {method: "POST", headers: { "Content-Type": "application/json" },
                                                   body: JSON.stringify(preJsonData) });

                const result = await response.json();
                console.log(result);
                } catch (error) {
                   console.error("Error:", error);
                }

		handleSearch();

	}

	async function handleSearch() {
		const { FirstName, LastName, PhoneNumber, Email } = getSearchData();
		console.log(FirstName);
                const preJsonData = { userid,FirstName, LastName, Email, PhoneNumber };
                console.log(preJsonData);
		
		console.log(preJsonData);
		// Remove keys where value is empty string
    		const filtered = Object.fromEntries(
        	Object.entries(preJsonData).filter(([_, v]) => v !== "")
    		);

    		const query = new URLSearchParams(filtered).toString();
		console.log(query);
    		const response = await fetch("../LAMPAPI/search_contact.php?" + query);
		console.log("Waiting for json");
    		const contacts = await response.json();
		console.log(contacts);

	
		const resultsDiv = document.getElementById("searchinfo");
		document.getElementById("searchinfo").style.display = "block";

    		// Clear previous content
    		resultsDiv.innerHTML = "";

    		if (!contacts || contacts.length === 0) {
			resultsDiv.classList.add("information-container");
        		resultsDiv.innerHTML = "<p>No contacts found.</p>";
        		return;
   		 }

    		// Create table inside the div container
    		const table = document.createElement("table");
    		table.classList.add("contact-table");

    		// Apply card styles to the parent div
    		resultsDiv.classList.add("information-container");

    		// Table header
    		const header = table.insertRow();
    		["First Name", "Last Name", "Email", "Phone Number", "", ""].forEach(text => {
        	const th = document.createElement("th");
        	th.textContent = text; // last two blank for buttons
        	header.appendChild(th);
    		});

    		// Table rows
    		contacts.forEach(contact => {
        	const row = table.insertRow();
        	["FirstName", "LastName", "Email", "PhoneNumber"].forEach(key => {
            	const cell = row.insertCell();
            	cell.textContent = contact[key] || "";
        	});

        	// Button 1
        	const btn1Cell = row.insertCell();
        	const btn1 = document.createElement("button");
        	btn1.textContent = "Edit"; // blank label
		btn1.style.width = "30px";
		btn1.style.height = "30px"
        	btn1.addEventListener("click", () => {
            	handleEdit(contact);
        	});
        	btn1Cell.appendChild(btn1);

        	// Button 2
        	const btn2Cell = row.insertCell();
        	const btn2 = document.createElement("button");
        	btn2.textContent = "Delete"; // blank label
		btn2.style.width = "50px";
		btn2.style.height = "30px"
        	btn2.addEventListener("click", () => {
            	handleDelete(contact);
        	});
        	btn2Cell.appendChild(btn2);
    		});

    		resultsDiv.appendChild(table);
	}

