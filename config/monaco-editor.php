<?php

return [
    'general' => [
        'enable-preview' => false,
        'show-full-screen-toggle' => false,
        'show-loader' => false,
        'font-size' => '15px',
        'line-numbers-min-chars' => 3,
        'automatic-layout' => true,
        'default-theme' => 'blackboard',
    ],
    'themes' => [
        'blackboard' => [
            'base' => 'vs-dark',
            'inherit' => true,
            'rules' => [
                [
                    'background' => '161F27',
                    'token' => '',
                ],
                [
                    'foreground' => 'aeaeae',
                    'token' => 'comment',
                ],
                [
                    'foreground' => 'd8fa3c',
                    'token' => 'constant',
                ],
                [
                    'foreground' => 'ff6400',
                    'token' => 'entity',
                ],
                [
                    'foreground' => 'fbde2d',
                    'token' => 'keyword',
                ],
                [
                    'foreground' => 'fbde2d',
                    'token' => 'storage',
                ],
                [
                    'foreground' => '61ce3c',
                    'token' => 'string',
                ],
                [
                    'foreground' => '61ce3c',
                    'token' => 'meta.verbatim',
                ],
                [
                    'foreground' => '8da6ce',
                    'token' => 'support',
                ],
                [
                    'foreground' => 'ab2a1d',
                    'fontStyle' => 'italic',
                    'token' => 'invalid.deprecated',
                ],
                [
                    'foreground' => 'f8f8f8',
                    'background' => '9d1e15',
                    'token' => 'invalid.illegal',
                ],
                [
                    'foreground' => 'ff6400',
                    'fontStyle' => 'italic',
                    'token' => 'entity.other.inherited-class',
                ],
                [
                    'foreground' => 'ff6400',
                    'token' => 'string constant.other.placeholder',
                ],
                [
                    'foreground' => 'becde6',
                    'token' => 'meta.function-call.py',
                ],
                [
                    'foreground' => '7f90aa',
                    'token' => 'meta.tag',
                ],
                [
                    'foreground' => '7f90aa',
                    'token' => 'meta.tag entity',
                ],
                [
                    'foreground' => 'ffffff',
                    'token' => 'entity.name.section',
                ],
                [
                    'foreground' => 'd5e0f3',
                    'token' => 'keyword.type.variant',
                ],
                [
                    'foreground' => 'f8f8f8',
                    'token' => 'source.ocaml keyword.operator.symbol',
                ],
                [
                    'foreground' => '8da6ce',
                    'token' => 'source.ocaml keyword.operator.symbol.infix',
                ],
                [
                    'foreground' => '8da6ce',
                    'token' => 'source.ocaml keyword.operator.symbol.prefix',
                ],
                [
                    'fontStyle' => 'underline',
                    'token' => 'source.ocaml keyword.operator.symbol.infix.floating-point',
                ],
                [
                    'fontStyle' => 'underline',
                    'token' => 'source.ocaml keyword.operator.symbol.prefix.floating-point',
                ],
                [
                    'fontStyle' => 'underline',
                    'token' => 'source.ocaml constant.numeric.floating-point',
                ],
                [
                    'background' => 'ffffff08',
                    'token' => 'text.tex.latex meta.function.environment',
                ],
                [
                    'background' => '7a96fa08',
                    'token' => 'text.tex.latex meta.function.environment meta.function.environment',
                ],
                [
                    'foreground' => 'fbde2d',
                    'token' => 'text.tex.latex support.function',
                ],
                [
                    'foreground' => 'ffffff',
                    'token' => 'source.plist string.unquoted',
                ],
                [
                    'foreground' => 'ffffff',
                    'token' => 'source.plist keyword.operator',
                ],
            ],
            'colors' => [
                'editor.foreground' => '#F8F8F8',
                'editor.background' => '#101519',
                'editor.selectionBackground' => '#5a5f63',
                'editor.lineHighlightBackground' => '#FFFFFF0F',
                'editorCursor.foreground' => '#FFFFFFA6',
                'editorWhitespace.foreground' => '#FFFFFF40',
            ],
        ],
        'iPlastic' => [
            'base' => 'vs',
            'inherit' => true,
            'rules' => [
                [
                    'background' => 'EEEEEEEB',
                    'token' => '',
                ],
                [
                    'foreground' => '009933',
                    'token' => 'string',
                ],
                [
                    'foreground' => '0066ff',
                    'token' => 'constant.numeric',
                ],
                [
                    'foreground' => 'ff0080',
                    'token' => 'string.regexp',
                ],
                [
                    'foreground' => '0000ff',
                    'token' => 'keyword',
                ],
                [
                    'foreground' => '9700cc',
                    'token' => 'constant.language',
                ],
                [
                    'foreground' => '990000',
                    'token' => 'support.class.exception',
                ],
                [
                    'foreground' => 'ff8000',
                    'token' => 'entity.name.function',
                ],
                [
                    'fontStyle' => 'bold underline',
                    'token' => 'entity.name.type',
                ],
                [
                    'fontStyle' => 'italic',
                    'token' => 'variable.parameter',
                ],
                [
                    'foreground' => '0066ff',
                    'fontStyle' => 'italic',
                    'token' => 'comment',
                ],
                [
                    'foreground' => 'ff0000',
                    'background' => 'e71a114d',
                    'token' => 'invalid',
                ],
                [
                    'background' => 'e71a1100',
                    'token' => 'invalid.deprecated.trailing-whitespace',
                ],
                [
                    'foreground' => '000000',
                    'background' => 'fafafafc',
                    'token' => 'text source',
                ],
                [
                    'foreground' => '0033cc',
                    'token' => 'meta.tag',
                ],
                [
                    'foreground' => '0033cc',
                    'token' => 'declaration.tag',
                ],
                [
                    'foreground' => '6782d3',
                    'token' => 'constant',
                ],
                [
                    'foreground' => '6782d3',
                    'token' => 'support.constant',
                ],
                [
                    'foreground' => '3333ff',
                    'fontStyle' => 'bold',
                    'token' => 'support',
                ],
                [
                    'fontStyle' => 'bold',
                    'token' => 'storage',
                ],
                [
                    'fontStyle' => 'bold underline',
                    'token' => 'entity.name.section',
                ],
                [
                    'foreground' => '000000',
                    'fontStyle' => 'bold',
                    'token' => 'entity.name.function.frame',
                ],
                [
                    'foreground' => '333333',
                    'token' => 'meta.tag.preprocessor.xml',
                ],
                [
                    'foreground' => '3366cc',
                    'fontStyle' => 'italic',
                    'token' => 'entity.other.attribute-name',
                ],
                [
                    'fontStyle' => 'bold',
                    'token' => 'entity.name.tag',
                ],
            ],
            'colors' => [
                'editor.foreground' => '#000000',
                'editor.background' => '#EEEEEEEB',
                'editor.selectionBackground' => '#BAD6FD',
                'editor.lineHighlightBackground' => '#0000001A',
                'editorCursor.foreground' => '#000000',
                'editorWhitespace.foreground' => '#B3B3B3F4',
            ],
        ],
    ],
];
