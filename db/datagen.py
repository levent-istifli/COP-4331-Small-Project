import random;

# potential domain names for emails
domains = ["gmail.com", "yahoo.com", "hotmail.com", "outlook.com", "aol.com", "icloud.com"]

# potential user name for contacts
names   = ["James", "Mary", "John", "Patricia", "Robert", "Jennifer", "Michael", "Linda",
           "William", "Elizabeth", "David", "Barbara", "Richard", "Susan", "Joseph", "Jessica",
           "Thomas", "Sarah", "Christopher", "Karen", "Charles", "Nancy", "Daniel", "Lisa",
           "Matthew", "Betty", "Anthony", "Helen", "Mark", "Sandra", "Donald", "Donna",
           "Steven", "Carol", "Paul", "Ruth", "Andrew", "Sharon", "Joshua", "Michelle",
           "Kenneth", "Laura", "Kevin", "Sarah", "Brian", "Kimberly", "George", "Deborah",
           "Timothy", "Dorothy", "Ronald", "Amy", "Jason", "Angela", "Edward", "Ashley",
           "Jeffrey", "Brenda", "Ryan", "Emma", "Jacob", "Olivia", "Gary", "Cynthia",
           "Nicholas", "Marie", "Eric", "Janet", "Jonathan", "Catherine", "Stephen", "Frances",
           "Larry", "Christine", "Justin", "Samantha", "Scott", "Debra", "Brandon", "Rachel",
           "Benjamin", "Carolyn", "Samuel", "Virginia", "Gregory", "Maria", "Alexander", "Heather",
           "Patrick", "Diane", "Frank", "Julie", "Raymond", "Joyce", "Jack", "Victoria"]

# generate random phone number
def gen_number():
    temp1 = random.randint(321, 959)
    temp2 = random.randint(100, 999)
    temp3 = random.randint(1000, 9999)
    return f'{temp1}-{temp2}-{temp3}'

# generate random email
def gen_email(firstname, lastname):
    domain = random.choice(domains)
    type = random.choice([1, 2, 3, 4])
    if type == 1:
        return f'{firstname.lower()}{lastname.lower()}@{domain}'
    elif type == 2:
        return f'{lastname.lower()}{firstname.lower()}@{domain}'
    elif type == 3:
        return f'{lastname.lower()}.{firstname.lower()}@{domain}'
    else :
        return f'{firstname.lower()}.{lastname.lower()}@{domain}'

# generate sql statement name
def gen_sql(size, filename):    

    with open(filename, "w") as file:
        file.write('-- Generating a random contact list for the first 5 users.\n')
        file.write('-- Only run after userid 1-5 are created\n')
        file.write('USE CONTACT_MANAGER;\n\n')

        for user_id in range(1, 6):
            file.write(f'-- Starting with user #{user_id}\n')
            file.write('INSERT INTO Contacts (FirstName, LastName, PhoneNumber, Email, UserID) VALUES\n')

            for i in range(size):
                # there's a bit of a limitation. the program does not generate
                # unique contact information
                firstname = random.choice(names)
                lastname  = random.choice(names)
                phonumber = gen_number();
                email     = gen_email(firstname, lastname)
                
                # adds data point
                file.write(f'("{firstname}", "{lastname}", "{phonumber}", "{email}", "{user_id}")')
                
                # continues or stop commands
                if i < size - 1: 
                    file.write(',\n')
                else:
                    file.write(';\n\n')
            
gen_sql(100, "data.sql")