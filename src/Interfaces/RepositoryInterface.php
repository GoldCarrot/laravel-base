<?php
/**
 * Created date 17.07.2020
 * @author Sergey Tyrgola <ts@GoldcarrotLaravel\Base.ru>
 */

namespace GoldcarrotLaravel\Base\Interfaces;


interface RepositoryInterface
{
    public function one($id);

    public function oneActive($id);

    public function all();

    public function allActive();

    public function paginate(array $params = [], int $limit = 20);

    public function paginateActive(array $params = [], int $limit = 20);

    public function search(array $params = [], $active = false);

    public function searchActive(array $params = []);

    public function find(array $params = [], $active = false);

    public function findActive(array $params = []);
}
