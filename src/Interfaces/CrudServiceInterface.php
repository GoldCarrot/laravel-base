<?php
/**
 * Created date 09.02.2021
 * @author Sergey Tyrgola <ts@GoldcarrotLaravel\Base.ru>
 */

namespace GoldcarrotLaravel\Base\Interfaces;

interface CrudServiceInterface
{
    public function create(array $data);

    public function update($model, array $data);

    public function destroy($model);
}
