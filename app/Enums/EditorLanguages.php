<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EditorLanguages: string implements HasLabel
{
    case plaintext = 'plaintext';
    case abap = 'abap';
    case apex = 'apex';
    case azcali = 'azcali';
    case bat = 'bat';
    case bicep = 'bicep';
    case cameligo = 'cameligo';
    case clojure = 'clojure';
    case coffeescript = 'coffeescript';
    case c = 'c';
    case cpp = 'cpp';
    case csharp = 'csharp';
    case csp = 'csp';
    case css = 'css';
    case cypher = 'cypher';
    case dart = 'dart';
    case dockerfile = 'dockerfile';
    case ecl = 'ecl';
    case elixir = 'elixir';
    case flow9 = 'flow9';
    case fsharp = 'fsharp';
    case go = 'go';
    case graphql = 'graphql';
    case handlebars = 'handlebars';
    case hcl = 'hcl';
    case html = 'html';
    case ini = 'ini';
    case java = 'java';
    case javascript = 'javascript';
    case julia = 'julia';
    case json = 'json';
    case kotlin = 'kotlin';
    case less = 'less';
    case lexon = 'lexon';
    case lua = 'lua';
    case liquid = 'liquid';
    case m3 = 'm3';
    case markdown = 'markdown';
    case mdx = 'mdx';
    case mips = 'mips';
    case msdax = 'msdax';
    case mysql = 'mysql';
    case objectivec = 'objective-c';
    case pascal = 'pascal';
    case pascaligo = 'pascaligo';
    case perl = 'perl';
    case pgsql = 'pgsql';
    case php = 'php';
    case pla = 'pla';
    case postiats = 'postiats';
    case powerquery = 'powerquery';
    case powershell = 'powershell';
    case proto = 'proto';
    case pug = 'pug';
    case python = 'python';
    case qsharp = 'qsharp';
    case r = 'r';
    case razor = 'razor';
    case redis = 'redis';
    case redshift = 'redshift';
    case restructuredtext = 'restructuredtext';
    case ruby = 'ruby';
    case rust = 'rust';
    case sb = 'sb';
    case scala = 'scala';
    case scheme = 'scheme';
    case scss = 'scss';
    case shell = 'shell';
    case sol = 'sol';
    case aes = 'aes';
    case sparql = 'sparql';
    case sql = 'sql';
    case st = 'st';
    case swift = 'swift';
    case systemverilog = 'systemverilog';
    case verilog = 'verilog';
    case tcl = 'tcl';
    case twig = 'twig';
    case typescript = 'typescript';
    case typespec = 'typespec';
    case vb = 'vb';
    case wgsl = 'wgsl';
    case xml = 'xml';
    case yaml = 'yaml';

    public static function fromWithAlias(string $match): self
    {
        return match ($match) {
            'h' => self::c,

            'cc', 'hpp' => self::cpp,

            'cs' => self::csharp,

            'class' => self::java,

            'htm' => self::html,

            'js', 'mjs', 'cjs' => self::javascript,

            'kt', 'kts' => self::kotlin,

            'md' => self::markdown,

            'm' => self::objectivec,

            'pl', 'pm' => self::perl,

            'php3', 'php4', 'php5', 'phtml' => self::php,

            'py', 'pyc', 'pyo', 'pyi' => self::python,

            'rdata', 'rds' => self::r,

            'rb', 'erb' => self::ruby,

            'sc' => self::scala,

            'sh', 'zsh' => self::shell,

            'ts', 'tsx' => self::typescript,

            'yml' => self::yaml,

            default => self::tryFrom($match) ?? self::plaintext,
        };
    }

    public function getLabel(): string
    {
        return $this->name;
    }
}
