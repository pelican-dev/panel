<?php

namespace App\Tests\Unit\Helpers;

use App\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ConvertToUtf8Test extends TestCase
{
    #[DataProvider('helperDataProvider')]
    public function test_helper(string $input, string $expected): void
    {
        $result = convert_to_utf8($input);
        $this->assertSame($expected, $result);
    }

    /**
     * Every output must be valid UTF-8, regardless of input encoding.
     */
    #[DataProvider('helperDataProvider')]
    public function test_output_is_valid_utf8(string $input): void
    {
        $result = convert_to_utf8($input);
        $this->assertTrue(mb_check_encoding($result, 'UTF-8'), 'Output is not valid UTF-8: ' . bin2hex($result));
    }

    /**
     * Running convert_to_utf8 twice must produce the same result as once.
     */
    #[DataProvider('helperDataProvider')]
    public function test_idempotent(string $input): void
    {
        $once = convert_to_utf8($input);
        $twice = convert_to_utf8($once);
        $this->assertSame($once, $twice, 'Double conversion changed the output (double-encoding bug): ' . bin2hex($once) . ' → ' . bin2hex($twice));
    }

    public static function helperDataProvider(): array
    {
        return [
            // UTF-8 passthrough - must never be re-encoded
            'ascii string' => ['hello world', 'hello world'],
            'empty string' => ['', ''],
            'utf8 accented cafe' => ["caf\xC3\xA9", "caf\xC3\xA9"],
            'utf8 emoji' => ["\xF0\x9F\x98\x80", "\xF0\x9F\x98\x80"],
            'utf8 cjk characters' => ["\xE4\xB8\xAD\xE6\x96\x87", "\xE4\xB8\xAD\xE6\x96\x87"],
            'utf8 cyrillic' => ["\xD0\x9F\xD1\x80\xD0\xB8\xD0\xB2\xD0\xB5\xD1\x82", "\xD0\x9F\xD1\x80\xD0\xB8\xD0\xB2\xD0\xB5\xD1\x82"], // Привет
            'utf8 bom preserved' => ["\xEF\xBB\xBFhello", "\xEF\xBB\xBFhello"],
            'utf8 null byte' => ["a\x00b", "a\x00b"],

            // Issue #2187 - small caps were double-encoded breaking Monaco display
            'utf8 small caps phrase (issue #2187)' => [
                "\xE1\xB4\x9B\xCA\x9C\xC9\xAA\xEA\x9C\xB1 \xC9\xAA\xEA\x9C\xB1 \xE1\xB4\x80\xC9\xB4 \xE1\xB4\x87x\xE1\xB4\x80\xE1\xB4\x8D\xE1\xB4\x98\xCA\x9F\xE1\xB4\x87",
                "\xE1\xB4\x9B\xCA\x9C\xC9\xAA\xEA\x9C\xB1 \xC9\xAA\xEA\x9C\xB1 \xE1\xB4\x80\xC9\xB4 \xE1\xB4\x87x\xE1\xB4\x80\xE1\xB4\x8D\xE1\xB4\x98\xCA\x9F\xE1\xB4\x87",
            ],

            // Minecraft § color codes - extremely common in game server files
            'utf8 minecraft section sign' => ["\xC2\xA7aGreen \xC2\xA7cRed", "\xC2\xA7aGreen \xC2\xA7cRed"],
            'iso-8859-1 minecraft section sign' => ["\xA7aGreen", "\xC2\xA7aGreen"],

            // PR #2199 double-encoding regression - UTF-8 é (\xC3\xA9) must NOT become \xC3\x83\xC2\xA9
            'no double encoding of utf8 e-acute' => ["\xC3\xA9", "\xC3\xA9"],
            'no double encoding of utf8 multi-byte' => ["\xC3\xBC\xC3\xA4\xC3\xB6", "\xC3\xBC\xC3\xA4\xC3\xB6"], // üäö

            // Issue #1606 - ISO-8859-1 files couldn't be edited
            'iso-8859-1 cafe (issue #1606)' => ["caf\xE9", "caf\xC3\xA9"],
            'iso-8859-1 german umlauts' => ["\xFC\xE4\xF6", "\xC3\xBC\xC3\xA4\xC3\xB6"], // üäö
            'iso-8859-1 full range latin' => ["\xE0\xE8\xEC\xF2\xF9", "\xC3\xA0\xC3\xA8\xC3\xAC\xC3\xB2\xC3\xB9"], // àèìòù
            'iso-8859-1 single high byte 0xFF' => ["\xFF", "\xC3\xBF"], // ÿ
            'iso-8859-1 copyright symbol' => ["\xA9 2026", "\xC2\xA9 2026"], // ©

            // PR #1896 - UTF-16LE files from Windows
            'utf16le with bom' => ["\xFF\xFEh\x00i\x00", 'hi'],
            'utf16be with bom' => ["\xFE\xFF\x00h\x00i", 'hi'],
            'utf16le with bom and accents' => ["\xFF\xFE" . "c\x00a\x00f\x00\xE9\x00", "caf\xC3\xA9"],
            'utf16le with bom multiline' => ["\xFF\xFE" . "l\x00i\x00n\x00e\x001\x00\x0A\x00l\x00i\x00n\x00e\x002\x00", "line1\nline2"],
            'utf16le bom only no content' => ["\xFF\xFE", ''],
            'utf16be bom only no content' => ["\xFE\xFF", ''],

            // PR #1991 - Windows-1252 smart quotes fall through to ISO-8859-1 fallback.
            // Bytes 0x80-0x9F are C1 control chars in ISO-8859-1 but printable in Windows-1252.
            // The result is valid UTF-8 (C1 control codepoints), not the "smart" characters,
            // but this is acceptable... it doesn't crash and the output is valid UTF-8.
            'windows-1252 smart quotes via iso-8859-1 fallback' => [
                "\x93Hello\x94",
                "\xC2\x93Hello\xC2\x94",
            ],
            'windows-1252 em dash via iso-8859-1 fallback' => [
                "word\x97word",
                "word\xC2\x97word",
            ],

            // Truncated UTF-8: max_edit_size can cut a file mid-character.
            // \xC3 starts a 2-byte sequence but has no continuation byte → invalid UTF-8 → ISO-8859-1 fallback
            'truncated utf8 2-byte sequence' => ["hello\xC3", "helloÃ"],
            'truncated utf8 3-byte sequence' => ["hello\xE2\x80", "helloâ\xC2\x80"],
            'truncated utf8 4-byte sequence' => ["hello\xF0\x9F\x98", "helloð\xC2\x9F\xC2\x98"],

            // Lone continuation bytes - invalid UTF-8, falls to ISO-8859-1
            'lone continuation byte 0x80' => ["\x80", "\xC2\x80"],
            'lone continuation bytes' => ["\x80\x81\x82", "\xC2\x80\xC2\x81\xC2\x82"],

            // UTF-16 without BOM - by design falls to ISO-8859-1, not detected as UTF-16.
            // This produces garbled output, but it's safe (valid UTF-8) and avoids false positives.
            'utf16le without bom falls to iso-8859-1' => ["h\x00i\x00", "h\x00i\x00"],

            // Game server config file content (real-world)
            'ascii config with equals and brackets' => [
                "[server]\nname=My Server\nport=25565",
                "[server]\nname=My Server\nport=25565",
            ],
            'yaml config with utf8' => [
                "motd: \"Willkommen \xC3\xBC\"\nmax-players: 20",
                "motd: \"Willkommen \xC3\xBC\"\nmax-players: 20",
            ],
            'windows line endings' => ["line1\r\nline2\r\n", "line1\r\nline2\r\n"],
            'tab separated values' => ["key\tvalue\nfoo\tbar", "key\tvalue\nfoo\tbar"],
        ];
    }
}
