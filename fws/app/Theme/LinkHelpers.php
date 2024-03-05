<?php
declare(strict_types=1);

namespace FWS\Theme;

/**
 * Collection oh helpers for manipulate with links.
 */
class LinkHelpers
{

    /**
     * Replace domain part in URL to point to public domain instead of admin.
     * This can be used in headless applications to translate URLs between "admin" and "public" domain.
     *
     * @param string $URL
     * @return string
     */
    public static function ConvertAdminToPublicLink(string $URL): string
    {
        $replacements = [
            '://fwsinternaladm.wpengine.com'  => '://internal.forwardslashny.com',  // live-server
            '://???????????'                  => '://stg.internal.fws.us',          // staging server
            '://fwsinternaldev.wpengine.com'  => '://dev.internal.fws.us',          // dev-server
            '://internal.lndo.site'           => '://localhost:3000',               // local
        ];
        return str_replace(array_keys($replacements), array_values($replacements), $URL);
    }


    /**
     * Reverse.
     *
     * @param string $URL
     * @return string
     */
    protected static function RevertPublicToAdminLink(string $URL): string
    {
        $home = home_url();
        $admin = '://' . explode('://', $home)[1];
        $public = explode('://', self::ConvertAdminToPublicLink($home), 2)[1];
        $public = '://' . explode('/', $public)[0];

        return str_replace($public, $admin, $URL);
    }


    /**
     * Convert full URL into relative link,
     * relative on root of domain but keep absolute if it points to external domain.
     *
     * @param string $URL
     * @param bool   $preserveExternal
     * @return string
     */
    public static function RelativizeLink(string $URL, bool $preserveExternal = true): string
    {
        // exit if already relative
        if (substr($URL, 0, 1) === '/') {
            return $URL;
        }

        // unconditionally remove domain part
        if (!$preserveExternal) {
            $parts = explode('://', $URL, 2);
            $parts = explode('/', end($parts), 2);
            return '/' . ($parts[1] ?? '');
        }

        // conditional removing domain part, try to find local domain
        $home = home_url() ?: '.com';
        $parts = explode($home, $URL, 2);

        // second chance, try with resolved domain
        if (count($parts) === 1) {
            $publicDomainPart = explode('://', self::ConvertAdminToPublicLink($home), 2)[1];
            $publicDomainPart = '://' . explode('/', $publicDomainPart)[0];
            $parts = explode($publicDomainPart, $URL);
            $parts = strlen($parts[0]) < 5 ? ['', $parts[1] ?? ''] : [$URL];
        }

        // don't relativize if pointing to 'uploads'
        if (strpos($URL, '/wp-content/uploads/') !== false) {
            return $URL;
        }

        // ret
        return $parts[0] === ''
            ? '/' . ltrim(end($parts) ?: '', ' /')  // trim spaces, prevent empty string, ensure it begins with '/'
            : $URL;
    }

}
