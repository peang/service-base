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
            'driver'    => Helpers::getValue(Base::getConfigs(), 'settings.db.driver'),
            'host'      => Helpers::getValue(Base::getConfigs(), 'settings.db.host'),
            'port'      => Helpers::getValue(Base::getConfigs(), 'settings.db.port'),
            'database'  => Helpers::getValue(Base::getConfigs(), 'settings.db.dbname'),
            'username'  => Helpers::getValue(Base::getConfigs(), 'settings.db.user'),
            'password'  => Helpers::getValue(Base::getConfigs(), 'settings.db.pass'),
            'charset'   => Helpers::getValue(Base::getConfigs(), 'settings.db.charset', 'utf8'),
            'collation' => Helpers::getValue(Base::getConfigs(), 'settings.db.collation', 'utf8_unicode_ci'),
        ]);

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }
}