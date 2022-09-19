# 7 - MVC

## About task
Creation of an MVC application that implements a database of the form: 
1. Car (make, model, country, series, year of manufacture) - this does not mean a specific car (which belongs to someone), but in general a make-model. The series refers to some kind of car line (for example, Audi A4 1st series, Audi A4 2nd series). And accordingly, in the model of the next series there must be a foreign key to the previous one.
2. Factory (name, country, car) - one car can have several factories around the world, and 1 factory can make several models / series.

## PHP version
Supporting **PHP version is _7.3_** and later

## Installation

#### 1. PHP 7.3 installation:
`sudo add-apt-repository ppa:ondrej/php`<br>
`sudo apt update` <br>
`sudo apt-get install php7.3 php7.3-bz2 php7.3-common php7.3-cgi php7.3-cli php7.3-dba php7.3-dev libphp7.3-embed php7.3-bcmath php7.3-gmp php7.3-fpm php7.3-mysql php7.3-tidy php7.3-sqlite3 php7.3-json php7.3-sybase php7.3-curl php7.3-ldap php7.3-phpdbg php7.3-imap php7.3-xml php7.3-xsl php7.3-intl php7.3-zip php7.3-odbc php7.3-mbstring php7.3-readline php7.3-gd php7.3-interbase php7.3-xmlrpc php7.3-snmp php7.3-soap php7.3-pspell
`

#### 2. Project installation:
1. GIT cloning: <br>`git clone ssh://git@git.lachestry.tech:2222/education/dev-interns-2022/7-mvc-sasha.git`
2. Copy **.env.sample** to the **.env** file in the root of the project and change the key values to suit your needs
3. Start localhost: <br>`php -S localhost:8080`

## Class description
### \App\PageHandler

###### 1. Description
    Сlass created for the main processing of any page

###### 2. Fields
    private static $instance;
Designed to store the **Facade-pattern** object and implements **SingleTon-pattern**

###### 3. Methods
    public function handlePage() - Main handling page method

    public static function getInstance(): self - Designed to get the Facade-pattern object

### \App\Blocks\BlockInterface
###### 1. Description
    Class created for the combine blocks into one class type

###### 2. Methods
    public function render(string $activeLink): AbstractBlock; - Rendering page

    public function getData(); - Getting data

    public function setData($data); - Setting data

    public function getHeader(string $separator): string; - Get the page title

### \App\Blocks\AbstractBlock
###### 1. Description
    Class created for the highlight the common parts of all block classes

###### 2. Fields
    protected $data = []; - Array of data

    protected $fileRender; - Name of file to render needed page

    protected $header = []; - Array of page title

    protected $commonStylesheetList = []; - Array of common stylesheet files

    protected $childStylesheetList = []; - Array of child block stylesheet files

    protected $activeLink; - Current active link

    protected $viewsPath = APP_ROOT . '/App/Views'; - Project path to App folder

    protected $srcPath = APP_ROOT . '/src'; - Project path to src folder

###### 3. Methods
    public function getHeader(string $separator = '&nbsp;'): string - Get page title separating by symbol

    public function getStylesheetList(): array - Get list stylesheet files

    public function getData() - Get data array

    public function setData($data): self - Set data array

    public function setHeader(array $headerData): self - Set page title array

    public function renderChildBlock() - Render the child block page

    public function footerSetData(): array - Set data to footer block

    public function render(string $activeLink): self - Render all of block`s page

    public function getActiveLink(): string - Get the current active link

### \App\Controllers\ControllerInterface

###### 1. Description
    Class created for the combine controllers into one class type

###### 2. Methods
    public function execute(): BlockInterface; - Using to create the necessary objects and bind them

### \App\Controllers\AbstractController

###### 1. Description
    Class created for the highlight the common parts of all controller classes

###### 2. Fields

    protected $getParams = []; - Request get params

###### 2. Methods

    public function __construct(array $params = []) - Set request get params

    public function redirectTo(string $webPath = '') - Using to user redirect

    public function getPostParam(string $key) - Using to get request post params

    public function isGetMethod(): bool - Using to check request method

    public function changeProperties(array $params,string $neededModel): bool - Using to change entity properties

### \App\Controllers\WrongController

###### 1. Description
    Class created for handle 500th error

### \App\Controllers\NotFoundController

###### 1. Description
    Class created for handle 404th error

### \App\Controllers\Database

###### 1. Description
    Class created to get database connection

###### 2. Fields
    private static $instance; - PDO object implements SingleTon-pattern

###### 3. Methods
    public static function getInstance(): \PDO - Get PDO object of database connection

### \App\Exception\Exception

###### 1. Description
    Class using to detect the user exception calling 404th error

### \App\Exception\SelectionException

###### 1. Description
    Class using to detect the user exception calling 404th error, caused wrong database selection

### \App\Models\Environment

###### 1. Description
    Class using to get the user environment properies

###### 2. Fields
    private static $instance; - Object of self class implements SingleTon-pattern
    private $dbHost; - Database host address
    private $dbName; - Database name
    private $dbUser; - Database user
    private $dbPass; - Database user password
    private $dbChar; - Database charset
    private $host;   - Web host address

###### 3. Methods
    public function __construct(string $envPath) - Needed to parse .env file and set data
    public static function getInstance(): self - Nedeed to get environment object
    public function getDbHost(): string
    public function getDbName(): string
    public function getDbUser(): string
    public function getDbPass(): string
    public function getDbChar(): string
    public function getHost(): string

### \App\Models\Recourse\AbstractResource

###### 1. Description
    Class created for the highlight the common parts of all recourse classes

###### 2. Methods
    protected function prepareValueSimpleMap(array $data, string $model): AbstractCarModel - Needed to set needed data into the model object
    protected function prepareValueMap(array $data, string $model = ''): array - Needed to set needed data into the list model objects
    protected function prepareKeyMap(array $haystack = []): array - Needed to change register of database fetch keys
    public function bindParamByMap(\PDOStatement $stmt,array $paramMap): \PDOStatement - Needed to bind params into stmt database object
    protected function deleteEntity(AbstractCarModel $model, string $tableName, string $tableRow): bool - Needed to delete the entity of database
    protected function deleteEntityList(array $modelList, string $tableName, string $tableRow): bool - Needed to delete the entity list of database

### \App\Models\Selection\SelectionInterface

###### 1. Description
    Class created for the combine selection classes into one class type

###### 2. Methods
    public function selectData(array $haystack): array; - Using to select needed data into the needed models

### \App\Models\AbstractCarModel

###### 1. Description
    Class created for the highlight the common parts of all model classes

###### 2. Fields
    protected $id;          - Id of model
    protected $name;        - Name of model
    protected $countryName; - Country name of model
    protected $countryId;   - Country id of model

###### 3. Methods
    public function setCountryId(int $countryId): self
    public function getCountryId(): int
    public function setCountryName(string $countryName): self
    public function getCountryName(): string
    public function setId(int $id): self
    public function getId(): int
    public function setName(string $name): self
    public function getName(): string

### \App\Router\Router

###### 1. Description
    Class created for the routing user to needed controller

###### 2. Methods
    public function chooseController(string $webPath): ?ControllerInterface - Neede to choose and create the controller

