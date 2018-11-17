<?php
namespace peang;

use peang\helpers\Helpers;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Builder;
use Phinx\Migration\AbstractMigration;

/**
 * Class Migration
 * @package base\abstracts
 */
class Migration extends AbstractMigration
{
    /** @var Manager $capsule */
    public $capsule;

    /** @var Builder $schema */
    public $schema;

    public function init()
    {
        $this->capsule = new Capsule();
        $this->capsule->addConnection([
            'driver'    => Helpers::getValue(Base::getConfigs(), 'settings.db.default.driver'),
            'host'      => Helpers::getValue(Base::getConfigs(), 'settings.db.default.host'),
            'port'      => Helpers::getValue(Base::getConfigs(), 'settings.db.default.port'),
            'database'  => Helpers::getValue(Base::getConfigs(), 'settings.db.default.dbname'),
            'username'  => Helpers::getValue(Base::getConfigs(), 'settings.db.default.user'),
            'password'  => Helpers::getValue(Base::getConfigs(), 'settings.db.default.pass'),
            'charset'   => Helpers::getValue(Base::getConfigs(), 'settings.db.default.charset', 'utf8'),
            'collation' => Helpers::getValue(Base::getConfigs(), 'settings.db.default.collation', 'utf8_unicode_ci'),
        ]);

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }
}