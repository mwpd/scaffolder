<?php
declare(strict_types=1);

namespace MWPD\Scaffolder\Prompt;

final class License extends Choice
{

    /**
     * Get the available options between which the user can choose.
     *
     * @return array Array of available options between which the user can choose.
     */
    protected function getOptions(): array
    {
        // @todo Look up license keys and names.
        return [
            'mit'    => 'MIT',
            'gplv2'  => 'Gnu Public License v2',
            'apache' => 'Apache',
        ];
    }
}
