# password-generator
php class for random and secure password generation

# Installation
```
composer require tbetool/php-password-generator
```
# Usage
### Create object
```
use TBETool\PasswordGenerator;

$passwordGenerator = new PasswordGenerator();
```
You can optionally pass following parameters to constructor
```
use TBETool\PasswordGenerator;

$passwordGenerator = new PasswordGenerator($length, $count, $characters);
```
#### Parameter Details
```
$length (int) : Length of the password to generate, Default: 8
$count (int) : No of passwords to generate, Default: 1
$characters (string): Characters to use while password generation
```
#### Supported Characters
1. lower_case
1. upper_case
1. numbers
1. special_symbols

#### Example with parameter
```
use TBETool\PasswordGenerator;

$passwordGenerator = new PasswordGenerator(16, 5, 'lower_case,numbers,special_symbols');
```

#### Set Parameters after creating object
Parameters set during object creation will be overwritten.
```
# Set lenght of password to 16
# params: (int) length
$passwordGenerator->setLength(16);

# Set number of passwords to generate
# params: (int) count
$passwordGenerator->setCount(5);

# Set characters to use in password
# params: (string) characters
$passwordGenerator->setCharacters('lower_case,numbers');
```

### Generate Password
This will return single password from all passwords generated
```
@return string of password
$password = $passwordGenerator->generate();
```

### Get All Generated Passwords
```
@return array of passwords
$passwords = $passwordGenerator->getPasswords();
```

### Get new password from generated passwords
```
@return string of new password
$password = $passwordGenerator->getPassword();
```

### Get last accessed password
```
@return string of last password retrieved
$password = $passwordGenerator->getLastPassword();
```

# Developer
Anuj Sharma (https://anujsh.gitlab.io) 

# Package
TBE (http://thebornengineer.com)
