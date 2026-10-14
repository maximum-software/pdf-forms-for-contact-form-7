# Localization strings context

This document lists every string of [`languages/pdf-forms-for-contact-form-7.pot`](../languages/pdf-forms-for-contact-form-7.pot) together with:

1. **Usage context** – where and how the string is used in the plugin (source file / function, UI element, placeholders).
2. **UI space limit** – reference dimensions and actual layout constraints of the UI element (measured with Chrome DevTools on the live WordPress admin, viewport 1280×800). Content width is not a fixed maximum; fit must be verified with rendered text, padding, neighbouring controls and wrapping.
3. **Research / typical translations** – how the string or its key terms are typically translated in each supported locale, based on the official translations of Contact Form 7, WooCommerce and WordPress core on translate.wordpress.org, Adobe Acrobat localized documentation (PDF terminology) and the WordPress locale style guides.
4. **WordPress glossary terms** – glossary terms (from the WordPress translation glossaries in [`docs/glossary/`](glossary/)) that occur in the string, with the translation the glossary requires for each locale, and context-specific interpretation notes.
5. **Adopted translation** – the wording in each locale’s gettext source and compiled catalog.

Supported locales: `ar`, `de_DE`, `es_ES`, `fr_FR`, `it_IT`, `ja`, `nl_NL`, `pl_PL`, `pt_PT`, `ru_RU`, `zh_CN`.

Priority rules used when translating: **actual fit where space is limited** > WordPress translation glossary, interpreted in context > Contact Form 7 / WordPress core terminology (consistency with the UI the user sees around the plugin) > industry terminology (Adobe Acrobat for PDF terms). Shortening must preserve recoverable meaning. Terminology should remain consistent within a locale, with explicit justification for space-driven exceptions.

## Product name policy

“PDF Forms Filler” is a descriptive product name, not a trademark. Its purpose is to tell new users immediately what the plugin does and to match what they search for, so it **is translated** in every locale. WordPress translates the `Plugin Name` header through the plugin’s text domain, so the Plugins list and localized WordPress.org plugin directories show the translated name; the English `Plugin Name` header and the `readme.txt` title stay identical in the source.

- Translate the meaning (a tool that fills PDF forms), not the English word order. The name must denote the product (a filler/tool), not the activity: an action noun alone (“filling of PDF forms”) names a process rather than a product. Use the locale’s established term for filling PDF forms (Adobe Acrobat localizations of *Fill & Sign* / *PDF form filler*) together with the glossary term for *form*.
- Where the locale has a natural agent noun, use it (de *Ausfüller*). Otherwise use “tool for filling PDF forms” (ar أداة, es herramienta, fr outil, it strumento, ja ツール, pt ferramenta, ru инструмент, zh 工具), the phrasing Adobe uses for *PDF form filler*. Avoid calqued agent nouns that are rare or ambiguous in the target language (for example Italian *compilatore* = compiler, Russian *заполнитель* = placeholder, Arabic *حشو* = padding/stuffing).
- Keep “PDF” and “CF7” in Latin script. CF7 abbreviates the Contact Form 7 product name; translate only the preposition “for”.
- Use one localized name per locale: the full name (#0) for the plugin header and every sentence or label that names the plugin (#54, #58, #66); the short name (#97) for the editor panel tab, the panel heading and panel references (#157).
- Quote the localized name with the locale’s quotation marks when it is embedded in a sentence or label, because a descriptive name can otherwise be read as part of the surrounding text. The standalone plugin name and panel tab are unquoted.
- Contact Form 7, Pdf.Ninja, Enterprise Extension, PHP Fileinfo and Maximum.Software remain untranslated product names. Preserve their exact casing and punctuation (including the dot in `Pdf.Ninja` and `Maximum.Software`); never translate, transliterate, or respell them. The lowercase `{a-href-pdf.ninja}` token is a placeholder (a link URL key), not the brand name, and stays lowercase and unchanged.

## Sources

- WordPress translation glossaries (CSV), stored in [`docs/glossary/`](glossary/): `https://translate.wordpress.org/locale/<code>/default/glossary/` (export `…/glossary/-export/`), codes: `ar` → `ar.csv`, `de_DE` → `de.csv`, `es_ES` → `es.csv`, `fr_FR` → `fr.csv`, `it_IT` → `it.csv`, `ja` → `ja.csv`, `pt_PT` → `pt.csv`, `ru_RU` → `ru.csv`, `zh_CN` → `zh-cn.csv`.
- Official translations (PO exports from translate.wordpress.org) used as reference for existing UI wording: Contact Form 7 (`wp-plugins/contact-form-7/stable`), WooCommerce (`wp-plugins/woocommerce/stable`, contains the Select2 “enhanced select” strings), WordPress core (`wp/dev`, `wp/dev/admin`).
- Adobe Acrobat / Acrobat Sign localized help (“Automatically flatten PDF documents when uploaded”: helpx.adobe.com/de|fr|it|cn/sign/config/send-settings/field-flattening.html; Adobe Experience Manager pt-BR “Nivelar campos de formulário”; adobe.com/jp “PDFファイルをフラット化する方法”) for the term *flatten*.
- Adobe Acrobat localized *Fill & Sign* pages for the product name: adobe.com/acrobat/online/sign-pdf.html (“free PDF form filler”) and its sa_ar (“أداة تعبئة نماذج PDF”), de (“PDF-Formulare ausfüllen”), es/ec (“herramienta para rellenar formularios PDF”), ca_fr (“outil de remplissage de formulaires PDF”), it (“compilazione dei moduli”), jp (“PDFフォーム入力ツール”) versions; adobe.com/br “preenchimento de formulário”; helpx.adobe.com/ru “Заполнение и подписание форм PDF”; helpx.adobe.com/cn “填写和签名 PDF 表单”.
- Japanese translation style guide: https://ja.wordpress.org/team/handbook/translation/translation-style-guide/

### Downloading the WordPress translation glossaries

The glossary CSVs in [`docs/glossary/`](glossary/) are not necessarily committed to the repository and may not exist on a fresh clone. When they are missing, download each locale's glossary CSV from the WordPress translation glossary page into the `docs/glossary` directory:

```
https://translate.wordpress.org/locale/<code>/default/glossary/
```

where `<code>` is the locale code. Use the CSV export link (`…/glossary/-export/`) on that page to obtain the CSV, and save it under the filename listed in the table below.

| Locale | Glossary page | Target file |
|---|---|---|
| `ar` | `https://translate.wordpress.org/locale/ar/default/glossary/` | `docs/glossary/ar.csv` |
| `de_DE` | `https://translate.wordpress.org/locale/de/default/glossary/` | `docs/glossary/de.csv` |
| `es_ES` | `https://translate.wordpress.org/locale/es/default/glossary/` | `docs/glossary/es.csv` |
| `fr_FR` | `https://translate.wordpress.org/locale/fr/default/glossary/` | `docs/glossary/fr.csv` |
| `it_IT` | `https://translate.wordpress.org/locale/it/default/glossary/` | `docs/glossary/it.csv` |
| `ja` | `https://translate.wordpress.org/locale/ja/default/glossary/` | `docs/glossary/ja.csv` |
| `pt_PT` | `https://translate.wordpress.org/locale/pt/default/glossary/` | `docs/glossary/pt.csv` |
| `ru_RU` | `https://translate.wordpress.org/locale/ru/default/glossary/` | `docs/glossary/ru.csv` |
| `zh_CN` | `https://translate.wordpress.org/locale/zh-cn/default/glossary/` | `docs/glossary/zh-cn.csv` |

### Downloading and reviewing third-party translations

The plugin's strings may also have been translated by third parties on translate.wordpress.org. Some of those translations may have been imported by us and approved by third parties, while others may have been imported and approved entirely by third parties; a few may be better than the AI translations in [`languages/`](../languages/). Review them before finalizing each locale.

#### Download

Download each locale's current translations from the plugin's translate.wordpress.org project into `docs/translate.wordpress.org`:

```
https://translate.wordpress.org/projects/wp-plugins/pdf-forms-for-contact-form-7/stable/<code>/default/export-translations/
```

where `<code>` is the locale code. Save the PO export as `docs/translate.wordpress.org/<code>.po`.

| Locale | Export URL | Target file |
|---|---|---|
| `ar` | `…/stable/ar/default/export-translations/` | `docs/translate.wordpress.org/ar.po` |
| `de_DE` | `…/stable/de/default/export-translations/` | `docs/translate.wordpress.org/de.po` |
| `es_ES` | `…/stable/es/default/export-translations/` | `docs/translate.wordpress.org/es.po` |
| `fr_FR` | `…/stable/fr/default/export-translations/` | `docs/translate.wordpress.org/fr.po` |
| `it_IT` | `…/stable/it/default/export-translations/` | `docs/translate.wordpress.org/it.po` |
| `ja` | `…/stable/ja/default/export-translations/` | `docs/translate.wordpress.org/ja.po` |
| `pt_PT` | `…/stable/pt/default/export-translations/` | `docs/translate.wordpress.org/pt.po` |
| `ru_RU` | `…/stable/ru/default/export-translations/` | `docs/translate.wordpress.org/ru.po` |
| `zh_CN` | `…/stable/zh-cn/default/export-translations/` | `docs/translate.wordpress.org/zh-cn.po` |

#### Decide which translation to keep

For each differing string, judge the third-party translation against the same rules used everywhere in this document:

1. **Product names** — Contact Form 7, Pdf.Ninja, Enterprise Extension, PHP Fileinfo and Maximum.Software must stay untranslated. A third-party translation that translates them is wrong.
2. **Terminology matrix** — the adopted terms in the Terminology matrix (e.g. flatten = Acoplar / フラット化 / Свести / 拼合, plugin = إضافة / extension / 插件) take precedence.
3. **WordPress glossary** — the locale glossary term, interpreted in context, takes precedence.
4. **Word sense** — reject literal/machine translations that pick the wrong sense (e.g. “Map Value” as geographic mapping, “No service” as “no signal”, “Copy” as a verb when it is a filename suffix).
5. **Grammar and formatting** — reject broken grammar, garbled concatenations, and wrong gender/number agreement.

If the third-party translation is better, copy its `msgstr` into the local PO and recompile the `.mo` with `composer make-mo`. Otherwise keep the local translation.

## UI space limits

Reference strings were measured with Chrome DevTools on the live WordPress admin (viewport 1280×800, WordPress 7.1, Contact Form 7 form editor + Integration page + Plugins list). The table below records English rendered control dimensions or containing-cell dimensions, as identified in the first column. These are not maximum translation widths: ordinary buttons grow, table columns reflow, and labels/headings can wrap. The PDF Form dialog provides 720 px of content width; the PDF Forms Filler editor panel provides 744 px at this viewport. Smaller screens, zoom, different fonts and the complete Arabic RTL interface require separate tests.

| UI element | English text | Measured width | Height |
|---|---|---|---|
| Editor panel tab | `PDF Forms Filler` | 132 px | 34 px |
| Primary button | `Attach a PDF File` | 134 px | 40 px |
| Primary button | `Add Mapping` | 112 px | 40 px |
| Primary button | `Insert and Link` | 126 px | 40 px |
| Primary button | `Insert & Link All` | 126 px | 40 px |
| Primary button | `Embed Image` | 114 px | 40 px |
| Secondary button | `Options` | 81 px | 40 px |
| Secondary button | `Delete` | 74 px | 40 px |
| Small button | `Map Value` | 71 px | 24 px |
| Small button | `Delete All` | 67 px | 24 px |
| Small button | `Go to PDF files` | 92 px | 24 px |
| Small button | `Go to Field Mappings` | 123 px | 24 px |
| Secondary button | `copy key` | 97 px | 40 px |
| Button | `Edit` | 57 px | 40 px |
| Primary button | `Save` | 61 px | 40 px |
| Secondary button | `Get New Key` | 110 px | 40 px |
| Inline toggle link | `Show Help` | 73 px | 18 px |
| Inline toggle link | `Show Tag Generator` | 119 px | 14 px |
| Visible CF7 toolbar button | `PDF Form` | 73 px | 26 px |
| Table header cell | `CF7 field/mail-tags` | 291 px (mapper) / 223 px (image tool) | 58 / 59 px |
| Table header | `PDF field` | 291 px (mapper) / 210 px (tag generator) | 58 px |
| Table header | `PDF file` | 223 px | 59 px |
| Table header | `Page` | 74 px | 59 px |
| Coordinate label | `Left` / `Top` / `Width` / `Height` | 26 / 24 / 38 / 43 px | 16 px |
| Plugins-list action link | `Docs` / `Support` | 29 / 48 px | 14 px |
| Integration label cells, English | `API Key` / `API URL` / `Administrator's Email Address` | 170 px (info); 91 / 91 / 114 px (edit) | 60 / 60 / 69 px (edit) |
| Integration email-label text, English | `Administrator's Email Address` | 104 px | 35 px (2 lines) |
| Select2 dropdown | (options / messages) | 281 px / 213 px | 28 px |

Character counts are not pixel measurements or reliable fit tests. Proportional glyph widths, Arabic shaping, CJK font choice, weight, padding and neighbouring controls affect the rendered result. “No hard limit” means the element wraps or grows within its containing layout, not that arbitrarily long text fits every viewport. Width-table entries marked “not measured” have no verified locale-specific fit result; character counts alone cannot establish overflow. The verified desktop measurements are summarised at the end of this document.

## Locale conventions

The table below records each locale's address form, quotation marks, parentheses and punctuation. In addition to the per-locale rules:

- Translate punctuation to the target language's counterparts, not the English marks (e.g. `« … »` for Russian and French quotes, `¿ … ?` / `¡ … !` for Spanish, `،` `؛` `؟` for Arabic, full-width `，。！？：；` for Chinese).
- Use the locale's quotation marks for quoted names and titles (see the "quoted plugin name" row in the Terminology matrix).
- Use the locale's parentheses: full-width `（ ）` for Chinese prose, half-width `( )` for Latin-script locales and Japanese. Keep half-width parentheses inside code/function names (e.g. `escapeshellarg()`) and placeholder templates (e.g. `{i}({size}){/i}`).
- Preserve locale-specific spacing rules (e.g. French non-breaking space before `? ! : ;` and inside `« »`).
- Keep numerals in Western (Latin) digits for version numbers, counts, and technical values; do not convert them to localized numeral systems. Preserve literal numbers in singular strings and the `{count}` placeholder in plural strings. Where a locale has multiple plural forms and no gettext plural support is available (the Select2 strings), use number-neutral wording.

| Locale | Translation conventions |
|---|---|
| `ar` | Modern Standard Arabic, neutral 2nd person (أنت); Arabic comma '، '; « » for quoted names; Latin product names kept in Latin script; glossary 'API' = 'واجهة برمجة التطبيقات (API)'; glossary 'plugin' = 'إضافة', which is also the verbal noun 'adding', so a named plugin takes the definite 'الإضافة «…»' (e.g. 'خطأ صادر عن الإضافة «…»', 'من الإضافة «…»'), never 'في إضافة …' (reads 'in adding …') or a sentence-initial bare 'إضافة …'. |
| `de_DE` | Informal 'du' (de_DE; 'Sie' is reserved for de_DE_formal); „…“ quotes; compounds hyphenated with product names (Contact-Form-7-…, Pdf.Ninja-API). |
| `es_ES` | Spain Spanish, informal 'tú' (glossary 'feel free' = 'no dudes'); « » quotes; 'solo' without accent. |
| `fr_FR` | 'vous'; typographic apostrophe ’; non-breaking space (U+00A0) before ? ! : ; and inside « »; 'extension' for plugin. |
| `it_IT` | Informal 'tu'; 'please' is not translated (glossary 'NON SI TRADUCE'); CF7 terms 'form-tag'/'mail-tag' kept as in CF7 it_IT. |
| `ja` | ja.wordpress.org translation style guide: half-width space between half-width alphanumerics and full-width text (not around digits), half-width ( ) ! ? with outer spaces, 「」 for UI/product names, 'すべて'/'ください'. |
| `pt_PT` | European Portuguese, impersonal/3rd person ('Tem a certeza que pretende…'); pre-AO90 spelling used by the WordPress pt_PT team and its glossary ('activar', 'seleccionar', 'actual'); never pt_BR forms ('arquivo', 'salvar', 'baixar'). |
| `ru_RU` | 'вы' (lowercase); « » quotes; number-neutral wording for {count} strings (no gettext plurals available); 'скачать' = download, 'загрузить' = upload. |
| `zh_CN` | Half-width space between Chinese and Latin letters/digits (as in WP core zh_CN), full-width punctuation （），。：！？ and “ ” quotes; '您'. |

## Terminology matrix

Preferred terms for each locale. Apply the appropriate grammatical form and distinguish product names, taxonomy tags, CF7 form/mail-tags, and other context-dependent senses.

| English | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` |
|---|---|---|---|---|---|---|---|---|---|
| Product names (Contact Form 7, Pdf.Ninja, Enterprise Extension) | not translated | not translated | not translated | not translated | not translated | not translated | not translated | not translated | not translated |
| PDF Forms Filler (short product name) | أداة تعبئة نماذج PDF | PDF-Formularausfüller | Herramienta de relleno de formularios PDF | Outil de remplissage de formulaires PDF | Strumento di compilazione moduli PDF | PDF フォーム入力ツール | Ferramenta de preenchimento de formulários PDF | Инструмент заполнения PDF-форм | PDF 表单填写工具 |
| PDF Forms Filler for CF7 (plugin name) | أداة تعبئة نماذج PDF لـ CF7 | PDF-Formularausfüller für CF7 | Herramienta de relleno de formularios PDF para CF7 | Outil de remplissage de formulaires PDF pour CF7 | Strumento di compilazione moduli PDF per CF7 | CF7 用 PDF フォーム入力ツール | Ferramenta de preenchimento de formulários PDF para CF7 | Инструмент заполнения PDF-форм для CF7 | 适用于 CF7 的 PDF 表单填写工具 |
| quoted plugin name in sentences/labels | «…» | „…“ | «…» | «&nbsp;…&nbsp;» | '…' | 「…」 | '…' | «…» | “…” |
| plugin | إضافة | Plugin | plugin | extension | plugin | プラグイン | plugin | плагин | 插件 |
| form / contact form | نموذج / نموذج الاتصال | Formular / Kontaktformular | formulario / formulario de contacto | formulaire / formulaire de contact | modulo / modulo di contatto | フォーム / お問い合わせフォーム | formulário / formulário de contacto | форма / контактная форма | 表单 / 联系表单 |
| field / PDF field | حقل / حقل PDF | Feld / PDF-Feld | campo / campo PDF | champ / champ PDF | campo / campo PDF | フィールド / PDF フィールド | campo / campo PDF | поле / поле PDF | 字段 / PDF 字段 |
| form-tag (CF7) | وسم النموذج | Formular-Tag | etiqueta de formulario | balise de formulaire | form-tag | フォームタグ | etiqueta de formulário | тег формы | 表单标签 |
| mail-tag (CF7) | وسم البريد | E-Mail-Tag | etiqueta de correo electrónico | balise d’e-mail | mail-tag | メールタグ | etiqueta de correio | почтовый тег | 邮件标签 |
| tag generator | مولّد الوسوم | Tag-Generator | generador de etiquetas | générateur de balises | generatore di tag | タグジェネレーター | gerador de etiquetas de variáveis | генератор тегов | 标签生成器 |
| (field) mapping / value mapping | تعيين / تعيين القيم | Zuordnung / Wertzuordnung | asignación / asignación de valores | correspondance / correspondance de valeurs | mappatura / mappatura dei valori | マッピング / 値マッピング | mapeamento / mapeamento de valores | сопоставление / сопоставление значений | 映射 / 值映射 |
| link (verb) | ربط | verknüpfen | enlazar | lier | collegare | リンクする | ligar | связать | 链接 |
| insert | إدراج | einfügen | insertar | insérer | inserire | 挿入 | inserir | вставить | 插入 |
| embed / image embedding | تضمين / تضمين الصور | einbetten / Bildeinbettung | incrustar / incrustación de imágenes | embarquer / images embarquées | incorporare / incorporamento immagini | 埋め込む / 画像埋め込み | incorporar / incorporação de imagens | встроить / встраивание изображений | 嵌入 / 图片嵌入 |
| attach / attachment | إرفاق / مُرفق | anhängen / Anhang | adjuntar / adjunto | joindre / fichier joint | allegare / allegato | 添付 / 添付ファイル | anexar / anexo | прикрепить / вложение | 附加 / 附件 |
| flatten (PDF) | تسطيح | Reduzieren | Acoplar | Aplatir | Appiattisci | フラット化 | Nivelar | Свести | 拼合 |
| upload / download | رفع / تنزيل | Upload, hochladen / Download, herunterladen | subida, subir / descarga, descargar | téléversement / téléchargement | upload, caricare / download, scaricare | アップロード / ダウンロード | carregamento / descarregamento, descarregar | загрузка / скачивание, скачать | 上传 / 下载 |
| delete / remove | حذف / إزالة | löschen / entfernen | borrar / quitar | supprimer / retirer | eliminare / rimuovere | 削除 / 削除 | eliminar / remover | удалить / удалить | 删除 / 移除 |
| settings / options | إعدادات / خيارات | Einstellungen / Optionen | ajustes / opciones | réglages / options | impostazioni / opzioni | 設定 / オプション | opções / opções | настройки / настройки | 设置 / 选项 |
| tool / button | أداة / زر | Werkzeug / Button | herramienta / botón | outil / bouton | strumento / pulsante | ツール / ボタン | ferramenta / botão | инструмент / кнопка | 工具 / 按钮 |
| warning / error | تحذير / خطأ | Warnung / Fehler | advertencia / error | avertissement / erreur | attenzione / errore | 警告 / エラー | atenção / erro | предупреждение / ошибка | 警告 / 错误 |
| deprecated | مهجور | veraltet | obsoleto | obsolète | deprecato | 非推奨 | obsoleto | устарел | 已弃用 |
| email / email address | بريد إلكتروني / عنوان البريد الإلكتروني | E-Mail / E-Mail-Adresse | correo electrónico / dirección de correo electrónico | e-mail / adresse e-mail | email / indirizzo email | メール / メールアドレス | email / endereço de email | письмо / адрес электронной почты | 邮件 / 邮箱地址 |
| API key | مفتاح واجهة برمجة التطبيقات (API) | API-Schlüssel | clave de la API | clé de l’API | chiave API | API キー | chave da API | ключ API | API 密钥 |
| nonce | الرمز المؤقت (nonce) | Nonce | nonce | nonce | nonce | nonce | nonce | nonce | 随机数 |
| post (ID) | مقالة | Beitrag | entrada | publication | articolo | 投稿 | artigo | запись | 文章 |
| panel | لوحة | Panel | panel | panneau | pannello | パネル | painel | панель | 面板 |

## Translation workflow

1. Identify every consumer of the source string: editor panel, PDF Form dialog, integration summary/edit forms, notices and frontend responses. Shared strings must remain meaningful and fit in each consumer.
2. Preserve named placeholders, interpolation tokens, product names, URLs, file paths and runtime formatting. Do not translate, reorder, rename, or alter any text enclosed in `{` and `}` — these are runtime placeholders (e.g. `{version}`, `{count}`, `{url}`, `{error-message}`, `{a-href-pdf.ninja}`, `{/a}`, `{icon}`, `{filename}`, `{size}`). Keep each placeholder's exact spelling and casing; the set of placeholders in a `msgstr` must match its `msgid` exactly. Determine whether the string is an action, noun, status, option, unit or instruction before choosing its wording.
3. Read the locale glossary entry together with its part of speech and description. Use official CF7 terminology for form/mail-tags; taxonomy-tag wording does not automatically apply. Use localized PDF-industry references for operations such as flattening.
4. Maintain the locale’s address form, grammar, punctuation, quotation marks, parentheses and spacing (see "Locale conventions"). Keep show/hide captions and references to button names consistent. Email-message and email-address labels are different concepts; CF7’s second mail template is not necessarily an autoresponder.
5. Measure candidate text in the complete localized layout, not an isolated English control. Include input-button captions, expanded help/toggle states and all neighbouring controls. Wrapping is acceptable only where the UI permits variable height; reject clipping, caption overlap and growth beyond the intended container.
6. Record the viewport, font, writing direction, control/cell dimensions, padding, usable text width, line count and containing-layout result. Count rendered lines by distinct vertical positions: mixed Arabic/Latin text can produce multiple rectangles on one line. Hidden controls provide no useful fit measurement.
7. Verify Arabic in the actual RTL admin when available. A local direction override checks bidirectional text and layout direction but does not validate the complete RTL stylesheet. Recheck narrower viewports, zoom and supported dialog versions independently.
8. Synchronize adopted-wording tables with the catalogs, validate gettext syntax/format placeholders, and regenerate compiled catalogs. Keep this document limited to current semantics, terminology, reference dimensions, constraints and translation procedure; task progress and change history belong outside it.

## Semantic checks

- Product and extension names remain recognizable: Contact Form 7, Pdf.Ninja, Enterprise Extension and PHP Fileinfo are not ordinary phrases to translate. PDF Forms Filler is the exception: it is a descriptive name and is translated according to the product name policy, consistently in every string that names the plugin or its panel.
- Mapping means assigning a source field/value to a PDF destination, not geographic mapping. Distinguish field mapping from value replacement and image placement.
- Translate button actions as actions and filename suffixes as nouns. In particular, the copy-key button is imperative; the copied-file suffix identifies a copy.
- Error and success messages must preserve their polarity and failure cause. “Unknown service” concerns a service provider, not a signal; a PDF snapshot is a page preview, not an operating-system backup.
- Character-count messages concern text characters, while selection-count messages concern list options. Preserve the distinction and use number-neutral phrasing where runtime plural handling is unavailable.

## Strings

### 0. `PDF Forms Filler for CF7`

- **Used in:** Plugin header `Plugin Name`; default notice label in `render_notice()`
- **Usage context:** Descriptive product name of the plugin, translated per the product name policy. Shown in the WordPress Plugins list, in localized WordPress.org plugin directories and as the default bold label of admin notices. It must tell a new user what the plugin does and match the locale’s search wording for filling PDF forms. “CF7” abbreviates Contact Form 7 and stays as is.
- **UI space limit:** No hard limit (plugin name in the Plugins list and notice label; wraps).

**Research / typical translations**

Adobe uses “PDF form filler” generically in English, and its localized pages render the concept as a tool for filling PDF forms: ar “أداة تعبئة نماذج PDF”, de “PDF-Formulare ausfüllen”, es “herramienta para rellenar formularios PDF”, fr “outil de remplissage de formulaires PDF”, it “compilazione dei moduli”, ja “PDFフォーム入力ツール”, pt “preenchimento de formulário”, ru “заполнение форм PDF”, zh “填写 PDF 表单”. German uses the agent noun *Ausfüller*; all other locales use the “tool for filling PDF forms” construction, which names a product as “filler” does and contains the common search phrase for filling PDF forms. An action noun alone (*Relleno*, *Remplissage*, *Compilazione*, *Preenchimento*, *Заполнение*, *تعبئة* = “filling”) is rejected because it names the activity, not the product. Literal agent-noun calques are rejected where they are rare or ambiguous: *Rellenador*, *Remplisseur*, *Preenchedor* (rare), *Compilatore* (compiler), *Заполнитель* (placeholder), *حشو* (padding), *填充器* (padding/filler material).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| forms | نماذج (noun) | – | – | – | – | – | – | – | – | ar glossary 'نماذج' used |
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) | plural forms used in the name |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) | kept in Latin script |

- `de_DE`: Acronym compound with hyphen after the acronym only (PDF-Formularausfüller).
- `ja`: Half-width space between PDF/CF7 and full-width text; 用 expresses “for CF7”.
- `ru_RU`: Hyphenated PDF-форм, as in the plugin description.
- `zh_CN`: 适用于 … 的 expresses “for CF7”; half-width spaces around CF7 and PDF.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | أداة تعبئة نماذج PDF لـ CF7 |
| `de_DE` | PDF-Formularausfüller für CF7 |
| `es_ES` | Herramienta de relleno de formularios PDF para CF7 |
| `fr_FR` | Outil de remplissage de formulaires PDF pour CF7 |
| `it_IT` | Strumento di compilazione moduli PDF per CF7 |
| `ja` | CF7 用 PDF フォーム入力ツール |
| `pt_PT` | Ferramenta de preenchimento de formulários PDF para CF7 |
| `ru_RU` | Инструмент заполнения PDF-форм для CF7 |
| `zh_CN` | 适用于 CF7 的 PDF 表单填写工具 |

### 1. `https://pdfformsfiller.org/`

- **Used in:** Plugin header `Plugin URI`
- **Usage context:** URL of the plugin website. Must not be translated (keep as is).
- **UI space limit:** No limit (URL, not displayed as text).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

No glossary term occurs in this string.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | https://pdfformsfiller.org/ |
| `de_DE` | https://pdfformsfiller.org/ |
| `es_ES` | https://pdfformsfiller.org/ |
| `fr_FR` | https://pdfformsfiller.org/ |
| `it_IT` | https://pdfformsfiller.org/ |
| `ja` | https://pdfformsfiller.org/ |
| `pt_PT` | https://pdfformsfiller.org/ |
| `ru_RU` | https://pdfformsfiller.org/ |
| `zh_CN` | https://pdfformsfiller.org/ |

### 2. `Build Contact Form 7 forms from PDF forms. Get PDFs auto-filled and attached to email messages and/or website responses on form submission.`

- **Used in:** Plugin header `Description`
- **Usage context:** Plugin description shown in the WordPress Plugins list. Marketing sentence: 'auto-filled' PDFs are attached to CF7 email messages and/or shown in the website response after the visitor submits the form.
- **UI space limit:** No hard limit (plugin description in the Plugins list; wraps to multiple lines).

**Research / typical translations**

Contact Form 7 is a product name. Its own localized documentation retains the name within surrounding translated text; translate the description of the plugin’s form-building and PDF-filling behavior, not the product name.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| build | – | – | – | Version (noun) / Compilation (noun) / Construire (verb) | – | – | – | – | – | fr_FR glossary 'Version/Compilation' for the noun; the verb 'Construire' is used |
| contact form | – | – | – | – | – | お問い合わせフォーム (noun) | – | – | – | only part of a product name / URL / placeholder – not translated |
| email | بريد إلكتروني (noun) | E-Mail (noun) / E-Mail-Adresse (noun) | correo electrónico (noun) | e-mail (noun) | email (noun) | メール (noun) / メールアドレス (noun) | email (noun) | – | 邮箱 (noun) |  |
| forms | نماذج (noun) | – | – | – | – | – | – | – | – |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |
| submission | – | – | – | entrée (noun) / envoi (noun) | – | – | – | – | – |  |
| website | موقع إلكتروني (noun) | Website (noun) | sitio web (noun) | site (noun) | sito web (noun) | サイト (noun) | site (noun) | – | 网站 (noun) |  |

- `fr_FR`: Glossary 'build' (verb) = 'Construire'; use typographic apostrophes and locale-appropriate non-breaking spaces consistently.
- `ja`: Follow the ja.wordpress.org style guide: half-width space between half-width and full-width characters, half-width ( ) ! ? with outer spaces, no space after colon-preceding text.
- `pt_PT`: European Portuguese with the pre-AO90 spelling used by WordPress pt_PT (glossary 'activar', 'selector', 'accionador'): 'ficheiro', 'guardar', 'utilizador', 'actual', 'seleccionar', 'desactivado'.
- `zh_CN`: Spacing between Chinese and Latin/digits as in WordPress core zh_CN (e.g. '文章 ID 无效。'), full-width punctuation.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | أنشئ نماذج Contact Form 7 من نماذج PDF. احصل على ملفات PDF تُملأ تلقائيًا وتُرفق برسائل البريد الإلكتروني و/أو ردود الموقع الإلكتروني عند إرسال النموذج. |
| `de_DE` | Erstelle Contact-Form-7-Formulare aus PDF-Formularen. Lass PDFs beim Absenden des Formulars automatisch ausfüllen und an E-Mail-Nachrichten und/oder Website-Antworten anhängen. |
| `es_ES` | Crea formularios de Contact Form 7 a partir de formularios PDF. Obtén archivos PDF rellenados automáticamente y adjuntos a los mensajes de correo electrónico y/o a las respuestas del sitio web al enviar el formulario. |
| `fr_FR` | Construisez des formulaires Contact Form 7 à partir de formulaires PDF. Obtenez des PDF remplis automatiquement et joints aux e-mails et/ou aux réponses du site lors de l’envoi du formulaire. |
| `it_IT` | Crea moduli di Contact Form 7 a partire da moduli PDF. Ottieni PDF compilati automaticamente e allegati ai messaggi email e/o alle risposte del sito web all'invio del modulo. |
| `ja` | PDF フォームから Contact Form 7 のフォームを作成します。フォーム送信時に PDF を自動入力し、メールメッセージやサイトの応答に添付します。 |
| `pt_PT` | Crie formulários do Contact Form 7 a partir de formulários PDF. Obtenha PDF preenchidos automaticamente e anexados às mensagens de email e/ou às respostas do site no envio do formulário. |
| `ru_RU` | Создавайте формы Contact Form 7 на основе PDF-форм. Получайте автоматически заполненные PDF-файлы, прикреплённые к письмам и/или к ответам сайта при отправке формы. |
| `zh_CN` | 从 PDF 表单构建 Contact Form 7 表单。在提交表单时自动填写 PDF，并将其附加到邮件和/或网站响应中。 |

### 3. `Maximum.Software`

- **Used in:** Plugin header `Author`
- **Usage context:** Company name. Must not be translated.
- **UI space limit:** No limit (author name).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

No glossary term occurs in this string.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | Maximum.Software |
| `de_DE` | Maximum.Software |
| `es_ES` | Maximum.Software |
| `fr_FR` | Maximum.Software |
| `it_IT` | Maximum.Software |
| `ja` | Maximum.Software |
| `pt_PT` | Maximum.Software |
| `ru_RU` | Maximum.Software |
| `zh_CN` | Maximum.Software |

### 4. `https://maximum.software/`

- **Used in:** Plugin header `Author URI`
- **Usage context:** URL of the author. Must not be translated.
- **UI space limit:** No limit (URL).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

No glossary term occurs in this string.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | https://maximum.software/ |
| `de_DE` | https://maximum.software/ |
| `es_ES` | https://maximum.software/ |
| `fr_FR` | https://maximum.software/ |
| `it_IT` | https://maximum.software/ |
| `ja` | https://maximum.software/ |
| `pt_PT` | https://maximum.software/ |
| `ru_RU` | https://maximum.software/ |
| `zh_CN` | https://maximum.software/ |

### 5. `Pdf.Ninja API`

- **Used in:** `WPCF7_Pdf_Ninja::get_service_name()` (modules/pdf-ninja.php)
- **Usage context:** Title of the Pdf.Ninja service card on the CF7 'Integration' admin page. 'Pdf.Ninja' is a product name; 'API' may be localised per locale conventions.
- **UI space limit:** Service card title on the Integration page; flex width, wraps. No hard limit.

**Research / typical translations**

'Pdf.Ninja' is a product name. Locales normally place 'API' according to their grammar (fr/es/it/pt 'API Pdf.Ninja' / 'API de Pdf.Ninja', de compound 'Pdf.Ninja-API'). Arabic glossary requires 'واجهة برمجة التطبيقات (API)'.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| api | واجهة برمجة التطبيقات (API) (abbreviation) | – | – | – | – | – | – | – | – |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) | only part of a product name / URL / placeholder – not translated |

- `ar`: Glossary: API = 'واجهة برمجة التطبيقات (API)'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | واجهة برمجة التطبيقات (API) لـ Pdf.Ninja |
| `de_DE` | Pdf.Ninja-API |
| `es_ES` | API de Pdf.Ninja |
| `fr_FR` | API Pdf.Ninja |
| `it_IT` | API Pdf.Ninja |
| `ja` | Pdf.Ninja API |
| `pt_PT` | API Pdf.Ninja |
| `ru_RU` | API Pdf.Ninja |
| `zh_CN` | Pdf.Ninja API |

### 6. `Failed to get the Pdf.Ninja API key on last attempt.`

- **Used in:** `WPCF7_Pdf_Ninja::get_key()` exception
- **Usage context:** Error thrown when an automatic attempt to obtain the key failed to obtain an API key automatically from the Pdf.Ninja API and does not retry automatically.
- **UI space limit:** No hard limit (exception / admin notice text; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| api key | – | – | – | Clé de l’API (noun) | – | – | – | – | – |  |
| last | – | – | – | Dernier (noun) | durare (verb) | – | – | – | – | it_IT glossary 'durare' is the verb 'to last'; here 'last attempt' (adjective) |
| on | – | – | – | – | on (noun) | – | – | – | – | it_IT glossary 'on' is the switch state; here a preposition |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) | only part of a product name / URL / placeholder – not translated |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | فشل الحصول على مفتاح واجهة برمجة التطبيقات (API) لـ Pdf.Ninja في المحاولة الأخيرة. |
| `de_DE` | Der Pdf.Ninja-API-Schlüssel konnte beim letzten Versuch nicht abgerufen werden. |
| `es_ES` | No se ha podido obtener la clave de la API de Pdf.Ninja en el último intento. |
| `fr_FR` | Échec de l’obtention de la clé de l’API Pdf.Ninja lors de la dernière tentative. |
| `it_IT` | Impossibile ottenere la chiave API di Pdf.Ninja all'ultimo tentativo. |
| `ja` | 前回の試行で Pdf.Ninja API キーを取得できませんでした。 |
| `pt_PT` | Falha ao obter a chave da API Pdf.Ninja na última tentativa. |
| `ru_RU` | Не удалось получить ключ API Pdf.Ninja при последней попытке. |
| `zh_CN` | 上次尝试获取 Pdf.Ninja API 密钥失败。 |

### 7. `Failed to determine the administrator's email address.`

- **Used in:** `WPCF7_Pdf_Ninja::get_admin_email()` exception
- **Usage context:** Error shown when the plugin cannot determine the WordPress administrator's email address needed to request a new API key.
- **UI space limit:** No hard limit (exception text; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| administrator | – | Administrator (noun) / Administratoren (noun) | – | administrateur/administratrice (noun) | amministratore (noun) | 管理者 (noun) | – | – | 管理员 (noun) |  |
| email address | – | E-Mail-Adresse (noun) | – | adresse e-mail (noun) | indirizzo email (expression) | – | – | – | – |  |

- `it_IT`: Glossary 'email' / 'email address' = 'email' / 'indirizzo email'; write email without a hyphen and use feminine agreement when it denotes a message.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تعذّر تحديد عنوان البريد الإلكتروني للمسؤول. |
| `de_DE` | Die E-Mail-Adresse des Administrators konnte nicht ermittelt werden. |
| `es_ES` | No se ha podido determinar la dirección de correo electrónico del administrador. |
| `fr_FR` | Impossible de déterminer l’adresse e-mail de l’administrateur. |
| `it_IT` | Impossibile determinare l'indirizzo email dell'amministratore. |
| `ja` | 管理者のメールアドレスを特定できませんでした。 |
| `pt_PT` | Não foi possível determinar o endereço de email do administrador. |
| `ru_RU` | Не удалось определить адрес электронной почты администратора. |
| `zh_CN` | 无法确定管理员的邮箱地址。 |

### 8. `PHP {version} or higher is required.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** One sentence of the Enterprise Extension compatibility report on the Integration page. {version} = minimum PHP version (e.g. 5.6.0). Sentences are concatenated with spaces, so each must be a complete sentence.
- **UI space limit:** No hard limit (compatibility report sentence; wraps in a table cell ~293px wide, multi-line).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| is required | – | – | – | – | – | 必要 (verb) | – | – | – |  |
| php | PHP (noun) | – | – | – | – | – | – | – | PHP (noun) |  |
| version | نسخة (noun) | – | – | – | – | – | – | – | – | only part of a product name / URL / placeholder – not translated |

- `ar`: Glossary: version = 'نسخة'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | مطلوب PHP {version} أو أعلى. |
| `de_DE` | PHP {version} oder höher ist erforderlich. |
| `es_ES` | Se requiere PHP {version} o superior. |
| `fr_FR` | PHP {version} ou supérieur est nécessaire. |
| `it_IT` | È necessario PHP {version} o superiore. |
| `ja` | PHP {version} 以上が必要です。 |
| `pt_PT` | É necessário o PHP {version} ou superior. |
| `ru_RU` | Требуется PHP {version} или выше. |
| `zh_CN` | 需要 PHP {version} 或更高版本。 |

### 9. `Windows platform is not supported.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility report sentence: the server runs Windows, which the Enterprise Extension does not support.
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| platform | – | – | – | plateforme (noun) | – | – | – | – | – |  |
| supported | – | – | compatible (noun) | – | – | – | – | – | – |  |
| windows | – | – | – | – | – | – | – | – | Windows (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | منصة Windows غير مدعومة. |
| `de_DE` | Die Windows-Plattform wird nicht unterstützt. |
| `es_ES` | La plataforma Windows no es compatible. |
| `fr_FR` | La plateforme Windows n’est pas prise en charge. |
| `it_IT` | La piattaforma Windows non è supportata. |
| `ja` | Windows プラットフォームはサポートされていません。 |
| `pt_PT` | A plataforma Windows não é suportada. |
| `ru_RU` | Платформа Windows не поддерживается. |
| `zh_CN` | 不支持 Windows 平台。 |

### 10. `PHP Fileinfo is disabled.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility report sentence: the PHP Fileinfo extension (finfo_open / mime_content_type) is disabled. 'PHP Fileinfo' is the name of a PHP extension and should not be translated.
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

PHP Fileinfo is the name of a PHP extension. Retain Fileinfo and translate only its disabled status.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| disabled | مُعطّل (adjective) | deaktiviert (verb) | desactivado (adverb) | – | disabilitato (adjective) | – | – | – | – |  |
| php | PHP (noun) | – | – | – | – | – | – | – | PHP (noun) |  |

- `es_ES`: Glossary 'disabled' = 'desactivado'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | PHP Fileinfo مُعطّل. |
| `de_DE` | PHP Fileinfo ist deaktiviert. |
| `es_ES` | PHP Fileinfo está desactivado. |
| `fr_FR` | PHP Fileinfo est désactivé. |
| `it_IT` | PHP Fileinfo è disabilitato. |
| `ja` | PHP Fileinfo が無効になっています。 |
| `pt_PT` | O PHP Fileinfo está desactivado. |
| `ru_RU` | Расширение PHP Fileinfo отключено. |
| `zh_CN` | PHP Fileinfo 已禁用。 |

### 11. `unknown`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Substituted into {cur-kernel-version} of string #12 when the Linux kernel version cannot be determined. Lowercase adjective ('unknown' version).
- **UI space limit:** Single lowercase adjective substituted into #12; keep very short (1 word).

**Research / typical translations**

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WooCommerce | غير معروف |
| `de_DE` | WooCommerce | unbekannt |
| `es_ES` | WooCommerce | desconocido |
| `fr_FR` | WooCommerce | inconnu |
| `it_IT` | WooCommerce | sconosciuto |
| `ja` | WooCommerce | 不明 |
| `pt_PT` | WooCommerce | desconhecido |
| `ru_RU` | WooCommerce | неизвестно |
| `zh_CN` | WooCommerce | 未知 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| unknown | غير معروف (adjective) | – | – | – | – | – | – | – | – |  |

- `ar`: Glossary 'غير معروف' used in feminine form 'غير معروفة' to agree with 'نسخة' (version).

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | غير معروفة |
| `de_DE` | unbekannt |
| `es_ES` | desconocida |
| `fr_FR` | inconnue |
| `it_IT` | sconosciuta |
| `ja` | 不明 |
| `pt_PT` | desconhecida |
| `ru_RU` | неизвестна |
| `zh_CN` | 未知 |

### 12. `Minimum kernel version supported is {min-kernel-version}, current kernel version is {cur-kernel-version}.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility report sentence about the Linux kernel version. {min-kernel-version} and {cur-kernel-version} are version numbers (or the word from #11).
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| current | – | – | – | – | – | – | – | – | 当前 (adjective) |  |
| supported | – | – | compatible (noun) | – | – | – | – | – | – |  |
| version | نسخة (noun) | – | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | الحد الأدنى لنسخة النواة المدعومة هو {min-kernel-version}، ونسخة النواة الحالية هي {cur-kernel-version}. |
| `de_DE` | Die minimal unterstützte Kernel-Version ist {min-kernel-version}, die aktuelle Kernel-Version ist {cur-kernel-version}. |
| `es_ES` | La versión mínima del kernel compatible es {min-kernel-version}; la versión actual del kernel es {cur-kernel-version}. |
| `fr_FR` | La version minimale du noyau prise en charge est {min-kernel-version}, la version actuelle du noyau est {cur-kernel-version}. |
| `it_IT` | La versione minima del kernel supportata è {min-kernel-version}, la versione attuale del kernel è {cur-kernel-version}. |
| `ja` | サポートされている最小カーネルバージョンは {min-kernel-version} で、現在のカーネルバージョンは {cur-kernel-version} です。 |
| `pt_PT` | A versão mínima do kernel suportada é {min-kernel-version}, a versão actual do kernel é {cur-kernel-version}. |
| `ru_RU` | Минимальная поддерживаемая версия ядра — {min-kernel-version}, текущая версия ядра — {cur-kernel-version}. |
| `zh_CN` | 支持的最低内核版本为 {min-kernel-version}，当前内核版本为 {cur-kernel-version}。 |

### 13. `Warning: Failed to detect kernel version support.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility report warning: the plugin could not detect whether the kernel version is supported.
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| support | – | – | es compatible con / dar soporte (verb) | support (noun) / prendre en charge (verb) | – | – | – | – | – |  |
| version | نسخة (noun) | – | – | – | – | – | – | – | – |  |
| warning | – | – | advertencia (noun) | avertissement (noun) | attenzione (noun) | – | atenção (noun) | – | – |  |

- `it_IT`: Glossary 'warning' = 'attenzione'.
- `pt_PT`: Glossary 'warning' = 'atenção'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تحذير: تعذّر اكتشاف دعم نسخة النواة. |
| `de_DE` | Warnung: Die Unterstützung der Kernel-Version konnte nicht erkannt werden. |
| `es_ES` | Advertencia: no se ha podido detectar la compatibilidad con la versión del kernel. |
| `fr_FR` | Avertissement&nbsp;: impossible de détecter la prise en charge de la version du noyau. |
| `it_IT` | Attenzione: impossibile rilevare il supporto della versione del kernel. |
| `ja` | 警告: カーネルバージョンのサポートを検出できませんでした。 |
| `pt_PT` | Atenção: não foi possível detectar o suporte da versão do kernel. |
| `ru_RU` | Предупреждение: не удалось определить, поддерживается ли версия ядра. |
| `zh_CN` | 警告：无法检测内核版本支持情况。 |

### 14. `PHP execute function (exec) is disabled.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility report sentence: the PHP function exec() is disabled. 'exec' is a function name and must stay as is.
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| disabled | مُعطّل (adjective) | deaktiviert (verb) | desactivado (adverb) | – | disabilitato (adjective) | – | – | – | – |  |
| function | دالة (noun) | – | – | – | – | – | – | – | 函数 (noun) |  |
| php | PHP (noun) | – | – | – | – | – | – | – | PHP (noun) |  |

- `ar`: Glossary 'function' = 'دالة'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | دالة التنفيذ في PHP (exec) مُعطّلة. |
| `de_DE` | Die PHP-Ausführungsfunktion (exec) ist deaktiviert. |
| `es_ES` | La función de ejecución de PHP (exec) está desactivada. |
| `fr_FR` | La fonction d’exécution PHP (exec) est désactivée. |
| `it_IT` | La funzione di esecuzione PHP (exec) è disabilitata. |
| `ja` | PHP の実行関数 (exec) が無効になっています。 |
| `pt_PT` | A função de execução do PHP (exec) está desactivada. |
| `ru_RU` | Функция выполнения PHP (exec) отключена. |
| `zh_CN` | PHP 执行函数（exec）已被禁用。 |

### 15. `PHP execute function (exec) is not working.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility report sentence: exec() is enabled but a test command did not work.
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| function | دالة (noun) | – | – | – | – | – | – | – | 函数 (noun) |  |
| php | PHP (noun) | – | – | – | – | – | – | – | PHP (noun) |  |
| working | – | – | – | – | elaborazione in corso (expression) | – | – | – | – | it_IT glossary 'elaborazione in corso' is the 'Working…' status; here 'is not working' / 'working with' |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | دالة التنفيذ في PHP (exec) لا تعمل. |
| `de_DE` | Die PHP-Ausführungsfunktion (exec) funktioniert nicht. |
| `es_ES` | La función de ejecución de PHP (exec) no funciona. |
| `fr_FR` | La fonction d’exécution PHP (exec) ne fonctionne pas. |
| `it_IT` | La funzione di esecuzione PHP (exec) non funziona. |
| `ja` | PHP の実行関数 (exec) が動作していません。 |
| `pt_PT` | A função de execução do PHP (exec) não está a funcionar. |
| `ru_RU` | Функция выполнения PHP (exec) не работает. |
| `zh_CN` | PHP 执行函数（exec）无法正常工作。 |

### 16. `Hosting environments with no access to /proc/self/stat are not supported.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility report sentence: the hosting environment does not allow reading /proc/self/stat (required by pdftk). Path must stay as is.
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| access | – | – | – | – | – | – | – | – | 访问 (verb) |  |
| hosting | – | – | alojamiento (noun) | – | – | – | alojamento (noun) | – | – |  |
| no | – | – | – | Aucun (noun) | – | – | – | – | – | fr_FR glossary 'Aucun' applies to 'No …' labels |
| stat | – | – | – | – | – | – | – | – | 统计数据 (noun) | only part of the path /proc/self/stat (not translated) |
| supported | – | – | compatible (noun) | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | بيئات الاستضافة التي لا يمكنها الوصول إلى /proc/self/stat غير مدعومة. |
| `de_DE` | Hosting-Umgebungen ohne Zugriff auf /proc/self/stat werden nicht unterstützt. |
| `es_ES` | Los entornos de alojamiento sin acceso a /proc/self/stat no son compatibles. |
| `fr_FR` | Les environnements d’hébergement sans accès à /proc/self/stat ne sont pas pris en charge. |
| `it_IT` | Gli ambienti di hosting senza accesso a /proc/self/stat non sono supportati. |
| `ja` | /proc/self/stat にアクセスできないホスティング環境はサポートされていません。 |
| `pt_PT` | Os ambientes de alojamento sem acesso a /proc/self/stat não são suportados. |
| `ru_RU` | Среды хостинга без доступа к /proc/self/stat не поддерживаются. |
| `zh_CN` | 不支持无法访问 /proc/self/stat 的托管环境。 |

### 17. `Warning: SELinux may cause problems with using required binaries. You may need to turn off SELinux or adjust its policies.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility report warning about SELinux blocking execution of bundled binaries.
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| adjust | ضبط (verb) | – | – | – | – | – | – | – | – |  |
| off | – | – | – | inactif (adjective) | off (noun) | – | – | – | – | it_IT/fr_FR glossary is the switch state; here 'turn off' |
| problems | مُشكلات (noun) | – | – | – | – | – | – | – | – |  |
| required | مطلوب (adjective) | erfordert (verb) / erforderlich (adjective) | obligatorio / requerido / necesario (adverb) | nécessaire (noun) / obligatoire (noun) | necessario (adjective) | 必須 (adjective) / 必要 (adjective) | – | – | – |  |
| warning | – | – | advertencia (noun) | avertissement (noun) | attenzione (noun) | – | atenção (noun) | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تحذير: قد يتسبب SELinux في حدوث مُشكلات عند استخدام الملفات الثنائية المطلوبة. قد تحتاج إلى إيقاف تشغيل SELinux أو ضبط سياساته. |
| `de_DE` | Warnung: SELinux kann Probleme bei der Verwendung der erforderlichen Binärdateien verursachen. Möglicherweise musst du SELinux deaktivieren oder seine Richtlinien anpassen. |
| `es_ES` | Advertencia: SELinux puede causar problemas al usar los binarios necesarios. Es posible que tengas que desactivar SELinux o ajustar sus políticas. |
| `fr_FR` | Avertissement&nbsp;: SELinux peut poser des problèmes lors de l’utilisation des binaires nécessaires. Vous devrez peut-être désactiver SELinux ou ajuster ses politiques. |
| `it_IT` | Attenzione: SELinux potrebbe causare problemi nell'utilizzo dei binari richiesti. Potrebbe essere necessario disattivare SELinux o modificarne le policy. |
| `ja` | 警告: SELinux により、必要なバイナリの使用に問題が発生する可能性があります。SELinux を無効にするか、ポリシーを調整する必要がある場合があります。 |
| `pt_PT` | Atenção: o SELinux pode causar problemas na utilização dos binários necessários. Poderá ter de desactivar o SELinux ou ajustar as suas políticas. |
| `ru_RU` | Предупреждение: SELinux может вызывать проблемы при использовании необходимых бинарных файлов. Возможно, вам потребуется отключить SELinux или настроить его политики. |
| `zh_CN` | 警告：SELinux 可能会导致使用所需的二进制文件时出现问题。您可能需要关闭 SELinux 或调整其策略。 |

### 18. `` PHP function `escapeshellarg()` is disabled. ``

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility report sentence: escapeshellarg() is disabled. Function name in backticks must stay as is.
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| disabled | مُعطّل (adjective) | deaktiviert (verb) | desactivado (adverb) | – | disabilitato (adjective) | – | – | – | – |  |
| function | دالة (noun) | – | – | – | – | – | – | – | 函数 (noun) |  |
| php | PHP (noun) | – | – | – | – | – | – | – | PHP (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | الدالة `escapeshellarg()` في PHP مُعطّلة. |
| `de_DE` | Die PHP-Funktion `escapeshellarg()` ist deaktiviert. |
| `es_ES` | La función de PHP `escapeshellarg()` está desactivada. |
| `fr_FR` | La fonction PHP `escapeshellarg()` est désactivée. |
| `it_IT` | La funzione PHP `escapeshellarg()` è disabilitata. |
| `ja` | PHP 関数 `escapeshellarg()` が無効になっています。 |
| `pt_PT` | A função PHP `escapeshellarg()` está desactivada. |
| `ru_RU` | Функция PHP `escapeshellarg()` отключена. |
| `zh_CN` | PHP 函数 `escapeshellarg()` 已被禁用。 |

### 19. `Warning: Bundled binaries are not available for this platform. Enterprise Extension 1 can use pdftk/qpdf/poppler/imagemagick package binaries ONLY if they are installed on the server system-wide.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility warning for non-x86_64 servers: bundled binaries are unavailable; Enterprise Extension 1 can only use system-wide installed pdftk/qpdf/poppler/imagemagick packages. 'ONLY' is emphasised in capitals.
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

Package names pdftk/qpdf/poppler/imagemagick and 'Enterprise Extension 1' are product names; emphasis 'ONLY' kept in capitals where the script has capitals.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| only | – | – | solo (adjective) / solo / solamente (adverb) | uniquement (noun) | – | – | – | – | – |  |
| package | – | – | – | – | – | – | – | – | 封装 (verb) | zh_CN glossary '封装' is the verb 'to package'; here software packages (软件包) |
| platform | – | – | – | plateforme (noun) | – | – | – | – | – |  |
| server | خادم (noun) | Server (noun) | – | serveur (noun) | server (noun) | サーバー (noun) | – | – | 服务器 (noun) |  |
| system | – | – | – | – | – | – | – | – | 系统 (noun) |  |
| warning | – | – | advertencia (noun) | avertissement (noun) | attenzione (noun) | – | atenção (noun) | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تحذير: الملفات الثنائية المضمّنة غير متاحة لهذه المنصة. يمكن لـ Enterprise Extension 1 استخدام الملفات الثنائية لحزم pdftk/qpdf/poppler/imagemagick فقط إذا كانت مثبّتة على الخادم على مستوى النظام بأكمله. |
| `de_DE` | Warnung: Für diese Plattform sind keine mitgelieferten Binärdateien verfügbar. Enterprise Extension 1 kann Binärdateien der Pakete pdftk/qpdf/poppler/imagemagick NUR verwenden, wenn diese systemweit auf dem Server installiert sind. |
| `es_ES` | Advertencia: los binarios incluidos no están disponibles para esta plataforma. Enterprise Extension 1 SOLO puede usar los binarios de los paquetes pdftk/qpdf/poppler/imagemagick si están instalados en el servidor para todo el sistema. |
| `fr_FR` | Avertissement&nbsp;: les binaires intégrés ne sont pas disponibles pour cette plateforme. Enterprise Extension 1 peut utiliser les binaires des paquets pdftk/qpdf/poppler/imagemagick UNIQUEMENT s’ils sont installés sur le serveur à l’échelle du système. |
| `it_IT` | Attenzione: i binari inclusi non sono disponibili per questa piattaforma. Enterprise Extension 1 può utilizzare i binari dei pacchetti pdftk/qpdf/poppler/imagemagick SOLO se sono installati sul server a livello di sistema. |
| `ja` | 警告: このプラットフォームではバンドルされたバイナリを利用できません。Enterprise Extension 1 は、pdftk/qpdf/poppler/imagemagick パッケージのバイナリがサーバーのシステム全体にインストールされている場合に限り、それらを使用できます。 |
| `pt_PT` | Atenção: os binários incluídos não estão disponíveis para esta plataforma. O Enterprise Extension 1 pode utilizar binários dos pacotes pdftk/qpdf/poppler/imagemagick APENAS se estiverem instalados no servidor ao nível de todo o sistema. |
| `ru_RU` | Предупреждение: встроенные бинарные файлы недоступны для этой платформы. Enterprise Extension 1 может использовать бинарные файлы пакетов pdftk/qpdf/poppler/imagemagick, ТОЛЬКО если они установлены на сервере для всей системы. |
| `zh_CN` | 警告：此平台没有可用的捆绑二进制文件。仅当 pdftk/qpdf/poppler/imagemagick 软件包的二进制文件已在服务器系统范围内安装时，Enterprise Extension 1 才能使用它们。 |

### 20. `Enterprise Extension 2 is not available for this platform.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility report sentence for non-x86_64 servers. 'Enterprise Extension 2' is a product name (paid add-on plugin).
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| platform | – | – | – | plateforme (noun) | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | Enterprise Extension 2 غير متاح لهذه المنصة. |
| `de_DE` | Enterprise Extension 2 ist für diese Plattform nicht verfügbar. |
| `es_ES` | Enterprise Extension 2 no está disponible para esta plataforma. |
| `fr_FR` | Enterprise Extension 2 n’est pas disponible pour cette plateforme. |
| `it_IT` | Enterprise Extension 2 non è disponibile per questa piattaforma. |
| `ja` | Enterprise Extension 2 はこのプラットフォームでは利用できません。 |
| `pt_PT` | O Enterprise Extension 2 não está disponível para esta plataforma. |
| `ru_RU` | Enterprise Extension 2 недоступно для этой платформы. |
| `zh_CN` | Enterprise Extension 2 不适用于此平台。 |

### 21. `Enterprise Extension 1 is supported.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility report result. 'Enterprise Extension 1' is a product name; keep it consistent with #19, #20, #46.
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

Enterprise Extension is a paid add-on product name. Retain the name and its version number consistently within service reports.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| supported | – | – | compatible (noun) | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | Enterprise Extension 1 مدعوم. |
| `de_DE` | Enterprise Extension 1 wird unterstützt. |
| `es_ES` | Enterprise Extension 1 es compatible. |
| `fr_FR` | Enterprise Extension 1 est prise en charge. |
| `it_IT` | Enterprise Extension 1 è supportata. |
| `ja` | Enterprise Extension 1 はサポートされています。 |
| `pt_PT` | O Enterprise Extension 1 é suportado. |
| `ru_RU` | Enterprise Extension 1 поддерживается. |
| `zh_CN` | 支持 Enterprise Extension 1。 |

### 22. `Enterprise Extension 1 is not supported.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility report result (negative).
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| supported | – | – | compatible (noun) | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | Enterprise Extension 1 غير مدعوم. |
| `de_DE` | Enterprise Extension 1 wird nicht unterstützt. |
| `es_ES` | Enterprise Extension 1 no es compatible. |
| `fr_FR` | Enterprise Extension 1 n’est pas prise en charge. |
| `it_IT` | Enterprise Extension 1 non è supportata. |
| `ja` | Enterprise Extension 1 はサポートされていません。 |
| `pt_PT` | O Enterprise Extension 1 não é suportado. |
| `ru_RU` | Enterprise Extension 1 не поддерживается. |
| `zh_CN` | 不支持 Enterprise Extension 1。 |

### 23. `Enterprise Extension 2 is supported.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility report result.
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| supported | – | – | compatible (noun) | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | Enterprise Extension 2 مدعوم. |
| `de_DE` | Enterprise Extension 2 wird unterstützt. |
| `es_ES` | Enterprise Extension 2 es compatible. |
| `fr_FR` | Enterprise Extension 2 est prise en charge. |
| `it_IT` | Enterprise Extension 2 è supportata. |
| `ja` | Enterprise Extension 2 はサポートされています。 |
| `pt_PT` | O Enterprise Extension 2 é suportado. |
| `ru_RU` | Enterprise Extension 2 поддерживается. |
| `zh_CN` | 支持 Enterprise Extension 2。 |

### 24. `Enterprise Extension 2 is not supported.`

- **Used in:** `get_enterprise_extension_support()`
- **Usage context:** Compatibility report result (negative).
- **UI space limit:** No hard limit (report sentence; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| supported | – | – | compatible (noun) | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | Enterprise Extension 2 غير مدعوم. |
| `de_DE` | Enterprise Extension 2 wird nicht unterstützt. |
| `es_ES` | Enterprise Extension 2 no es compatible. |
| `fr_FR` | Enterprise Extension 2 n’est pas prise en charge. |
| `it_IT` | Enterprise Extension 2 non è supportata. |
| `ja` | Enterprise Extension 2 はサポートされていません。 |
| `pt_PT` | O Enterprise Extension 2 não é suportado. |
| `ru_RU` | Enterprise Extension 2 не поддерживается. |
| `zh_CN` | 不支持 Enterprise Extension 2。 |

### 25. `Failed to connect to {url}`

- **Used in:** `WPCF7_Pdf_Ninja::api_get/api_post` exception
- **Usage context:** Error when the HTTP request to the API server fails. {url} = request URL; the transport error is appended after the sentence.
- **UI space limit:** No hard limit (exception text; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| url | – | URL (noun) | URL (noun) | URL (noun) / URL (noun) | URL (noun) | – | URL (noun) | URL (noun) | URL (noun) | only part of a product name / URL / placeholder – not translated |

- `pt_PT`: Glossary 'connection' = 'ligação'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | فشل الاتصال بـ {url} |
| `de_DE` | Verbindung zu {url} fehlgeschlagen |
| `es_ES` | No se ha podido conectar con {url} |
| `fr_FR` | Impossible de se connecter à {url} |
| `it_IT` | Impossibile connettersi a {url} |
| `ja` | {url} への接続に失敗しました |
| `pt_PT` | Falha na ligação a {url} |
| `ru_RU` | Не удалось подключиться к {url} |
| `zh_CN` | 无法连接到 {url} |

### 26. `Failed to decode API server response`

- **Used in:** `WPCF7_Pdf_Ninja::api_request` exception
- **Usage context:** Error when the JSON response of the API server cannot be decoded.
- **UI space limit:** No hard limit (exception text; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| api | واجهة برمجة التطبيقات (API) (abbreviation) | – | – | – | – | – | – | – | – |  |
| decode | فك ترميز (verb) | – | – | – | – | – | – | – | 解码 (verb) |  |
| server | خادم (noun) | Server (noun) | – | serveur (noun) | server (noun) | サーバー (noun) | – | – | 服务器 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | فشل فك ترميز استجابة خادم واجهة برمجة التطبيقات (API) |
| `de_DE` | Die Antwort des API-Servers konnte nicht dekodiert werden |
| `es_ES` | No se ha podido decodificar la respuesta del servidor de la API |
| `fr_FR` | Impossible de décoder la réponse du serveur de l’API |
| `it_IT` | Impossibile decodificare la risposta del server API |
| `ja` | API サーバーの応答をデコードできませんでした |
| `pt_PT` | Falha ao descodificar a resposta do servidor da API |
| `ru_RU` | Не удалось декодировать ответ сервера API |
| `zh_CN` | 无法解码 API 服务器的响应 |

### 27. `Pdf.Ninja API server did not send an expected response`

- **Used in:** modules/pdf-ninja.php (6 places)
- **Usage context:** Error when the Pdf.Ninja API server's response does not contain the expected data. Meaning: 'the server did not return the expected response'.
- **UI space limit:** No hard limit (error notice; wraps).

**Research / typical translations**

Use natural API-error wording equivalent to “did not return/send the expected response.” Preserve the failure meaning and distinguish it from a malformed-response error.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| api | واجهة برمجة التطبيقات (API) (abbreviation) | – | – | – | – | – | – | – | – |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) | only part of a product name / URL / placeholder – not translated |
| server | خادم (noun) | Server (noun) | – | serveur (noun) | server (noun) | サーバー (noun) | – | – | 服务器 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | لم يُرسل خادم واجهة برمجة التطبيقات (API) لـ Pdf.Ninja الاستجابة المتوقعة |
| `de_DE` | Der Pdf.Ninja-API-Server hat nicht die erwartete Antwort gesendet |
| `es_ES` | El servidor de la API de Pdf.Ninja no ha enviado la respuesta esperada |
| `fr_FR` | Le serveur de l’API Pdf.Ninja n’a pas envoyé la réponse attendue |
| `it_IT` | Il server API di Pdf.Ninja non ha inviato la risposta prevista |
| `ja` | Pdf.Ninja API サーバーから想定された応答がありませんでした |
| `pt_PT` | O servidor da API Pdf.Ninja não enviou a resposta esperada |
| `ru_RU` | Сервер API Pdf.Ninja не отправил ожидаемый ответ |
| `zh_CN` | Pdf.Ninja API 服务器未返回预期的响应 |

### 28. `Failed to download a file from the API server`

- **Used in:** `WPCF7_Pdf_Ninja::api_download_file`
- **Usage context:** Error when downloading a generated file (filled PDF / page image) from the API server fails.
- **UI space limit:** No hard limit (error notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| api | واجهة برمجة التطبيقات (API) (abbreviation) | – | – | – | – | – | – | – | – |  |
| download | تنزيل (verb) | herunterladen (verb) / Download (noun) | – | – | – | – | descarregamento (noun) / descarregar (verb) | скачать (verb) | 下载 (verb) |  |
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| server | خادم (noun) | Server (noun) | – | serveur (noun) | server (noun) | サーバー (noun) | – | – | 服务器 (noun) |  |

- `ru_RU`: Glossary: 'download' = 'скачать', 'upload' = 'загрузить'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | فشل تنزيل ملف من خادم واجهة برمجة التطبيقات (API) |
| `de_DE` | Eine Datei konnte nicht vom API-Server heruntergeladen werden |
| `es_ES` | No se ha podido descargar un archivo del servidor de la API |
| `fr_FR` | Impossible de télécharger un fichier depuis le serveur de l’API |
| `it_IT` | Impossibile scaricare un file dal server API |
| `ja` | API サーバーからファイルをダウンロードできませんでした |
| `pt_PT` | Falha ao descarregar um ficheiro do servidor da API |
| `ru_RU` | Не удалось скачать файл с сервера API |
| `zh_CN` | 无法从 API 服务器下载文件 |

### 29. `Unknown attachment URL`

- **Used in:** `WPCF7_Pdf_Ninja::get_attachment_url`
- **Usage context:** Error: the URL of a PDF attachment (media library file) is unknown.
- **UI space limit:** No hard limit (error notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| attachment | مُرفق (noun) | Anhang (noun) | adjunto (noun) | fichier joint (noun) | allegato (noun) | 添付ファイル (noun) | – | вложение (noun) | – |  |
| unknown | غير معروف (adjective) | – | – | – | – | – | – | – | – |  |
| url | – | URL (noun) | URL (noun) | URL (noun) / URL (noun) | URL (noun) | – | URL (noun) | URL (noun) | URL (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | عنوان URL للمُرفق غير معروف |
| `de_DE` | Unbekannte URL des Anhangs |
| `es_ES` | URL del adjunto desconocida |
| `fr_FR` | URL du fichier joint inconnue |
| `it_IT` | URL dell'allegato sconosciuto |
| `ja` | 不明な添付ファイル URL |
| `pt_PT` | URL do anexo desconhecido |
| `ru_RU` | Неизвестный URL вложения |
| `zh_CN` | 未知的附件 URL |

### 30. `File is not accessible in the local file system`

- **Used in:** `WPCF7_Pdf_Ninja::get_attachment_path`
- **Usage context:** Error: the attachment file cannot be accessed on the local server file system.
- **UI space limit:** No hard limit (error notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| system | – | – | – | – | – | – | – | – | 系统 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | لا يمكن الوصول إلى الملف في نظام الملفات المحلي |
| `de_DE` | Auf die Datei kann im lokalen Dateisystem nicht zugegriffen werden |
| `es_ES` | No se puede acceder al archivo en el sistema de archivos local |
| `fr_FR` | Le fichier n’est pas accessible dans le système de fichiers local |
| `it_IT` | Il file non è accessibile nel file system locale |
| `ja` | ローカルファイルシステム内のファイルにアクセスできません |
| `pt_PT` | O ficheiro não está acessível no sistema de ficheiros local |
| `ru_RU` | Файл недоступен в локальной файловой системе |
| `zh_CN` | 无法在本地文件系统中访问该文件 |

### 31. `Failed to create file`

- **Used in:** modules/pdf-ninja.php, pdf-forms-for-contact-form-7.php (4 places)
- **Usage context:** Error when writing a (temporary) file on the server fails.
- **UI space limit:** No hard limit (error notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| create | – | erstellen (verb) / erstelle (verb) / erstellt (verb) | – | – | – | – | – | – | 创建 (verb) |  |
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | فشل إنشاء الملف |
| `de_DE` | Die Datei konnte nicht erstellt werden |
| `es_ES` | No se ha podido crear el archivo |
| `fr_FR` | Impossible de créer le fichier |
| `it_IT` | Impossibile creare il file |
| `ja` | ファイルの作成に失敗しました |
| `pt_PT` | Falha ao criar o ficheiro |
| `ru_RU` | Не удалось создать файл |
| `zh_CN` | 无法创建文件 |

### 32. `Failed to encode JSON data`

- **Used in:** `WPCF7_Pdf_Ninja::api_fill` / `api_image`
- **Usage context:** Error when the request data cannot be encoded as JSON.
- **UI space limit:** No hard limit (error notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| encode | ترميز (verb) | – | – | – | – | – | – | – | 编码 (verb) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | فشل ترميز بيانات JSON |
| `de_DE` | Die JSON-Daten konnten nicht codiert werden |
| `es_ES` | No se han podido codificar los datos JSON |
| `fr_FR` | Impossible d’encoder les données JSON |
| `it_IT` | Impossibile codificare i dati JSON |
| `ja` | JSON データのエンコードに失敗しました |
| `pt_PT` | Falha ao codificar os dados JSON |
| `ru_RU` | Не удалось закодировать данные JSON |
| `zh_CN` | 无法编码 JSON 数据 |

### 33. `Nonce mismatch`

- **Used in:** AJAX handlers (6 places)
- **Usage context:** Error returned by admin AJAX requests when the WordPress nonce (security token) check fails. 'Nonce' is a WordPress technical term (glossary: keep 'nonce' in most locales).
- **UI space limit:** No hard limit (error notice; wraps).

**Research / typical translations**

A nonce is the WordPress request-verification token. Preserve the technical meaning of a nonce mismatch, using the locale’s established terminology rather than a generic disagreement or missing-current-value phrase.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| nonce | – | Nonce (noun) | nonce (noun) | nonce (noun) | nonce (noun) | nonce (noun) | – | – | 随机数 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | عدم تطابق الرمز المؤقت (nonce) |
| `de_DE` | Nonce stimmt nicht überein |
| `es_ES` | El nonce no coincide |
| `fr_FR` | Le nonce ne correspond pas |
| `it_IT` | Nonce non corrispondente |
| `ja` | nonce が一致しません |
| `pt_PT` | O nonce não corresponde |
| `ru_RU` | Несоответствие nonce |
| `zh_CN` | 随机数不匹配 |

### 34. `Permission denied`

- **Used in:** AJAX handlers / Integration form (3 places)
- **Usage context:** Error returned when the current user lacks the required capability (e.g. cannot edit the contact form).
- **UI space limit:** No hard limit (error notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| permission | صلاحية (noun) | Berechtigung (noun) / Recht (noun) | – | droit (noun) | autorizzazione (noun) | 権限 (noun) / パーミッション (noun) | – | – | 权限 (noun) |  |

- `ar`: Glossary 'permission' = 'صلاحية'.
- `fr_FR`: Glossary 'permission' = 'droit' → 'Droits insuffisants'.
- `it_IT`: Glossary 'permission' = 'autorizzazione'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | الصلاحية مرفوضة |
| `de_DE` | Berechtigung verweigert |
| `es_ES` | Permiso denegado |
| `fr_FR` | Droits insuffisants |
| `it_IT` | Autorizzazione negata |
| `ja` | 権限がありません |
| `pt_PT` | Permissão negada |
| `ru_RU` | Доступ запрещён |
| `zh_CN` | 权限不足 |

### 35. `copy key`

- **Used in:** Integration page button (`#copy-pdf-ninja-key-btn`) and JS `copy_key_label`
- **Usage context:** Button label next to the API key: copies the key to the clipboard. Lowercase in source; keep lowercase in translations (unless the language has no lowercase).
- **UI space limit:** BUTTON label, measured 97px wide (h40) for the English 'copy key'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

The button copies an API key to the clipboard. Use an imperative copy action, as in WordPress/WooCommerce button captions; do not describe a key used for copying.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| copy | نسخ (verb) | – | – | – | – | – | – | – | 复制 (noun) / 复制 (verb) |  |
| key | – | – | clave (noun) | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | نسخ المفتاح | not measured |
| `de_DE` | schlüssel kopieren | not measured |
| `es_ES` | copiar clave | not measured |
| `fr_FR` | copier la clé | not measured |
| `it_IT` | copia chiave | not measured |
| `ja` | キーをコピー | not measured |
| `pt_PT` | copiar chave | not measured |
| `ru_RU` | копировать ключ | not measured |
| `zh_CN` | 复制密钥 | not measured |

### 36. `copied!`

- **Used in:** JS `copied_label` (js/integration.js)
- **Usage context:** Temporary button label shown for 1 second after the key has been copied. Same meaning as WP core 'Copied!'. Lowercase in source; keep lowercase in translations (unless the language has no lowercase).
- **UI space limit:** Temporary caption on the copy-key button; English 'copy key' measures about 97×40px before the caption changes. The copied-state width is unmeasured and must not be assumed to equal 97px. Check both captions with neighbouring controls; no character-count maximum is established.

**Research / typical translations**

WP core 'Copied!' = de 'Kopiert!', es '¡Copiado!', fr 'Copié !', it 'Copiato!', ja 'コピーしました。', pt 'Copiado!', ru 'Скопировано!', zh '已复制！', ar 'تم النسخ!'. Use grammatical agreement with 'the key' where the language requires it (es/fr/it/pt).

**WordPress glossary terms**

No glossary term occurs in this string.

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | تم النسخ! | not measured |
| `de_DE` | kopiert! | not measured |
| `es_ES` | ¡copiada! | not measured |
| `fr_FR` | copiée&nbsp;! | not measured |
| `it_IT` | copiata! | not measured |
| `ja` | コピーしました。 | not measured |
| `pt_PT` | copiada! | not measured |
| `ru_RU` | скопировано! | not measured |
| `zh_CN` | 已复制！ | not measured |

### 37. `This service provides functionality for working with PDF files via {a-href-pdf.ninja}Pdf.Ninja API{/a}.`

- **Used in:** Integration page service card (`display_info`)
- **Usage context:** Description of the Pdf.Ninja service. {a-href-pdf.ninja}...{/a} wraps a link to https://pdf.ninja; keep the placeholders around the product name 'Pdf.Ninja API'.
- **UI space limit:** No hard limit (service card description paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| api | واجهة برمجة التطبيقات (API) (abbreviation) | – | – | – | – | – | – | – | – |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |
| service | – | – | – | – | – | – | – | – | 服务 (noun) |  |
| working | – | – | – | – | elaborazione in corso (expression) | – | – | – | – | it_IT glossary 'elaborazione in corso' is the 'Working…' status; here 'is not working' / 'working with' |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | توفر هذه الخدمة وظائف للعمل مع ملفات PDF عبر {a-href-pdf.ninja}واجهة برمجة التطبيقات (API) لـ Pdf.Ninja{/a}. |
| `de_DE` | Dieser Dienst stellt Funktionen für die Arbeit mit PDF-Dateien über die {a-href-pdf.ninja}Pdf.Ninja-API{/a} bereit. |
| `es_ES` | Este servicio ofrece funciones para trabajar con archivos PDF mediante la {a-href-pdf.ninja}API de Pdf.Ninja{/a}. |
| `fr_FR` | Ce service fournit des fonctionnalités permettant de travailler avec des fichiers PDF via l’{a-href-pdf.ninja}API Pdf.Ninja{/a}. |
| `it_IT` | Questo servizio fornisce funzionalità per lavorare con i file PDF tramite l'{a-href-pdf.ninja}API Pdf.Ninja{/a}. |
| `ja` | このサービスは、{a-href-pdf.ninja}Pdf.Ninja API{/a} を介して PDF ファイルを操作する機能を提供します。 |
| `pt_PT` | Este serviço disponibiliza funcionalidades para trabalhar com ficheiros PDF através da {a-href-pdf.ninja}API Pdf.Ninja{/a}. |
| `ru_RU` | Этот сервис предоставляет функции для работы с PDF-файлами через {a-href-pdf.ninja}API Pdf.Ninja{/a}. |
| `zh_CN` | 此服务通过 {a-href-pdf.ninja}Pdf.Ninja API{/a} 提供处理 PDF 文件的功能。 |

### 38. `API Key`

- **Used in:** Integration page (info + edit forms)
- **Usage context:** Table row / form label for the API key. Same as CF7 'API Key'.
- **UI space limit:** Label cells measure about 170px in the info card and 91px in the English edit form (60px row height). These are containing-cell widths, not text widths or fixed translation limits; content can expand the edit column and wrap.

**Research / typical translations**

Contact Form 7 uses: ar 'مفتاح API', de 'API-Schlüssel', es 'Clave de la API', fr 'Clé de l’API', it 'Chiave API', ja 'API キー', pt 'Chave de API', ru 'Ключ API', zh 'API密钥'.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | Contact Form 7 | مفتاح API |
| `de_DE` | Contact Form 7 | API-Schlüssel |
| `es_ES` | Contact Form 7 | Clave de la API |
| `fr_FR` | Contact Form 7 | Clé de l’API |
| `it_IT` | Contact Form 7 | Chiave API |
| `ja` | Contact Form 7 | API キー |
| `pt_PT` | Contact Form 7 | Chave de API |
| `ru_RU` | Contact Form 7 | Ключ API |
| `zh_CN` | Contact Form 7 | API密钥 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| api key | – | – | – | Clé de l’API (noun) | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | مفتاح واجهة برمجة التطبيقات (API) |
| `de_DE` | API-Schlüssel |
| `es_ES` | Clave de la API |
| `fr_FR` | Clé de l’API |
| `it_IT` | Chiave API |
| `ja` | API キー |
| `pt_PT` | Chave da API |
| `ru_RU` | Ключ API |
| `zh_CN` | API 密钥 |

### 39. `API URL`

- **Used in:** Integration page (info + edit forms)
- **Usage context:** Label of the API base URL field.
- **UI space limit:** Integration summary: 170px header cell, 160px usable text width. Arabic عنوان URL لواجهة برمجة التطبيقات (API) wraps over 2 lines; row height ~66px. Edit form: 91px cell, 81px usable text width, 3 lines, row height ~88px. Both Arabic tables remain within the 463px content area at 1280×800. Wrapping is permitted; this is not a single-line caption.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| api | واجهة برمجة التطبيقات (API) (abbreviation) | – | – | – | – | – | – | – | – |  |
| url | – | URL (noun) | URL (noun) | URL (noun) / URL (noun) | URL (noun) | – | URL (noun) | URL (noun) | URL (noun) |  |

- `zh_CN`: Glossary 'URL' = 'URL'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | عنوان URL لواجهة برمجة التطبيقات (API) |
| `de_DE` | API-URL |
| `es_ES` | URL de la API |
| `fr_FR` | URL de l’API |
| `it_IT` | URL API |
| `ja` | API URL |
| `pt_PT` | URL da API |
| `ru_RU` | URL API |
| `zh_CN` | API URL |

### 40. `API version`

- **Used in:** Integration page (info + edit forms)
- **Usage context:** Label of the radio-button group used to choose the API version.
- **UI space limit:** Label cells use the integration table layout (about 170px in the info card and 91px in the English edit form). These are reference cell widths, not maximum text widths; translations can wrap and affect column allocation.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| api | واجهة برمجة التطبيقات (API) (abbreviation) | – | – | – | – | – | – | – | – |  |
| version | نسخة (noun) | – | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | نسخة واجهة برمجة التطبيقات (API) |
| `de_DE` | API-Version |
| `es_ES` | Versión de la API |
| `fr_FR` | Version de l’API |
| `it_IT` | Versione API |
| `ja` | API バージョン |
| `pt_PT` | Versão da API |
| `ru_RU` | Версия API |
| `zh_CN` | API 版本 |

### 41. `Version 1`

- **Used in:** Integration page radio button
- **Usage context:** Radio button label: API version 1.
- **UI space limit:** Radio button label, inline after the radio; flex. Keep short (~12 Latin chars).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| version | نسخة (noun) | – | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | النسخة 1 |
| `de_DE` | Version 1 |
| `es_ES` | Versión 1 |
| `fr_FR` | Version 1 |
| `it_IT` | Versione 1 |
| `ja` | バージョン 1 |
| `pt_PT` | Versão 1 |
| `ru_RU` | Версия 1 |
| `zh_CN` | 版本 1 |

### 42. `Version 2`

- **Used in:** Integration page radio button
- **Usage context:** Radio button label: API version 2.
- **UI space limit:** Radio button label, inline; flex. Keep short (~12 Latin chars).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| version | نسخة (noun) | – | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | النسخة 2 |
| `de_DE` | Version 2 |
| `es_ES` | Versión 2 |
| `fr_FR` | Version 2 |
| `it_IT` | Versione 2 |
| `ja` | バージョン 2 |
| `pt_PT` | Versão 2 |
| `ru_RU` | Версия 2 |
| `zh_CN` | 版本 2 |

### 43. `Data Security`

- **Used in:** Integration page section label
- **Usage context:** Row heading for the data security option (certificate verification).
- **UI space limit:** Row heading, ~170px column. Reference measurement only; no verified character-count maximum is established. Evaluate wrapping and surrounding controls in the actual layout.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

No glossary term occurs in this string.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | أمان البيانات |
| `de_DE` | Datensicherheit |
| `es_ES` | Seguridad de los datos |
| `fr_FR` | Sécurité des données |
| `it_IT` | Sicurezza dei dati |
| `ja` | データセキュリティ |
| `pt_PT` | Segurança dos dados |
| `ru_RU` | Безопасность данных |
| `zh_CN` | 数据安全 |

### 44. `Ignore certificate verification errors`

- **Used in:** Integration page checkbox (`nosslverify`)
- **Usage context:** Checkbox: ignore TLS/SSL certificate verification errors when connecting to the API server.
- **UI space limit:** Checkbox label, inline after checkbox; wraps within ~293px cell. No hard limit but keep concise.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| certificate | – | – | – | – | – | – | – | – | 证书 (noun) |  |
| errors | – | Fehler (noun) | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تجاهل أخطاء التحقق من الشهادة |
| `de_DE` | Fehler bei der Zertifikatsprüfung ignorieren |
| `es_ES` | Ignorar los errores de verificación de certificados |
| `fr_FR` | Ignorer les erreurs de vérification des certificats |
| `it_IT` | Ignora gli errori di verifica del certificato |
| `ja` | 証明書の検証エラーを無視する |
| `pt_PT` | Ignorar erros de verificação de certificados |
| `ru_RU` | Игнорировать ошибки проверки сертификата |
| `zh_CN` | 忽略证书验证错误 |

### 45. `Warning: Using plain HTTP or disabling certificate verification can lead to data leaks.`

- **Used in:** Integration page
- **Usage context:** Warning shown below the API settings: plain (unencrypted) HTTP or disabled certificate verification may leak data.
- **UI space limit:** No hard limit (warning paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| certificate | – | – | – | – | – | – | – | – | 证书 (noun) |  |
| lead | – | – | cliente potencial (noun) | prospect (noun) | – | – | – | – | – | glossary sense is a sales 'lead' (prospect); in the string it is the verb 'lead to' |
| plain | – | einfach (adjective) | – | – | – | – | – | – | – |  |
| warning | – | – | advertencia (noun) | avertissement (noun) | attenzione (noun) | – | atenção (noun) | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تحذير: قد يؤدي استخدام HTTP العادي أو تعطيل التحقق من الشهادة إلى تسرّب البيانات. |
| `de_DE` | Warnung: Die Verwendung von einfachem HTTP oder das Deaktivieren der Zertifikatsprüfung kann zu Datenlecks führen. |
| `es_ES` | Advertencia: usar HTTP simple o desactivar la verificación de certificados puede provocar fugas de datos. |
| `fr_FR` | Avertissement&nbsp;: l’utilisation du HTTP simple ou la désactivation de la vérification des certificats peut entraîner des fuites de données. |
| `it_IT` | Attenzione: l'utilizzo di HTTP semplice o la disabilitazione della verifica del certificato può causare perdite di dati. |
| `ja` | 警告: プレーン HTTP を使用したり、証明書の検証を無効にしたりすると、データが漏洩する可能性があります。 |
| `pt_PT` | Atenção: utilizar HTTP simples ou desactivar a verificação de certificados pode causar fugas de dados. |
| `ru_RU` | Предупреждение: использование незашифрованного HTTP или отключение проверки сертификата может привести к утечке данных. |
| `zh_CN` | 警告：使用纯 HTTP 或禁用证书验证可能会导致数据泄露。 |

### 46. `Enterprise Extension`

- **Used in:** Integration page row heading (link to the Enterprise Extension store page)
- **Usage context:** Product name of the paid add-on ('Enterprise Extension'); followed by the compatibility report (#8-#24). Keep consistent with how the product name is rendered in the other strings.
- **UI space limit:** Row heading / link, ~170px column. Product name; keep intact.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

No glossary term occurs in this string.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | Enterprise Extension |
| `de_DE` | Enterprise Extension |
| `es_ES` | Enterprise Extension |
| `fr_FR` | Enterprise Extension |
| `it_IT` | Enterprise Extension |
| `ja` | Enterprise Extension |
| `pt_PT` | Enterprise Extension |
| `ru_RU` | Enterprise Extension |
| `zh_CN` | Enterprise Extension |

### 47. `Edit`

- **Used in:** Integration page buttons (2 places)
- **Usage context:** Button that opens the API settings edit form. Same as WP core 'Edit'.
- **UI space limit:** BUTTON label, measured 57px wide (h40) for 'Edit'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

Exact match in CF7/WP core: ar 'تحرير', de 'Bearbeiten', es 'Editar', fr 'Modifier', it 'Modifica', ja '編集', pt 'Editar', ru 'Изменить', zh '编辑'.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | Contact Form 7 | تحرير |
| `de_DE` | Contact Form 7 | Bearbeiten |
| `es_ES` | Contact Form 7 | Editar |
| `fr_FR` | Contact Form 7 | Modifier |
| `it_IT` | Contact Form 7 | Modifica |
| `ja` | Contact Form 7 | 編集 |
| `pt_PT` | Contact Form 7 | Editar |
| `ru_RU` | Contact Form 7 | Изменить |
| `zh_CN` | Contact Form 7 | 编辑 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| edit | تحرير (verb) | – | – | modifier (verb) | – | – | – | – | 编辑 (noun) / 编辑 (verb) |  |

- `fr_FR`: Glossary 'edit' = 'modifier'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تحرير |
| `de_DE` | Bearbeiten |
| `es_ES` | Editar |
| `fr_FR` | Modifier |
| `it_IT` | Modifica |
| `ja` | 編集 |
| `pt_PT` | Editar |
| `ru_RU` | Изменить |
| `zh_CN` | 编辑 |

### 48. `Error!`

- **Used in:** Integration page error template (`top-message`)
- **Usage context:** Heading/message shown when the service card cannot be displayed due to an error.
- **UI space limit:** No hard limit (error heading; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| error | خطأ (noun) | Fehler (noun) | – | erreur (noun) | errore (noun) | エラー (noun) | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | خطأ! |
| `de_DE` | Fehler! |
| `es_ES` | ¡Error! |
| `fr_FR` | Erreur&nbsp;! |
| `it_IT` | Errore! |
| `ja` | エラー ! |
| `pt_PT` | Erro! |
| `ru_RU` | Ошибка! |
| `zh_CN` | 错误！ |

### 49. `The following form allows you to edit your API settings.`

- **Used in:** Integration edit page
- **Usage context:** Intro sentence above the API settings form.
- **UI space limit:** No hard limit (intro paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| allows you | – | – | – | vous permet de (verb) | – | – | – | – | – |  |
| api | واجهة برمجة التطبيقات (API) (abbreviation) | – | – | – | – | – | – | – | – |  |
| edit | تحرير (verb) | – | – | modifier (verb) | – | – | – | – | 编辑 (noun) / 编辑 (verb) |  |
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) |  |
| settings | إعدادات (noun) | – | ajustes (noun) | Réglages (noun) | – | – | opções (noun) | настройки (noun) | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | يتيح لك النموذج التالي تحرير إعدادات واجهة برمجة التطبيقات (API). |
| `de_DE` | Mit dem folgenden Formular kannst du deine API-Einstellungen bearbeiten. |
| `es_ES` | El siguiente formulario te permite editar los ajustes de la API. |
| `fr_FR` | Le formulaire suivant vous permet de modifier les réglages de l’API. |
| `it_IT` | Il seguente modulo ti consente di modificare le impostazioni dell'API. |
| `ja` | 次のフォームで API 設定を編集できます。 |
| `pt_PT` | O formulário seguinte permite-lhe editar as opções da API. |
| `ru_RU` | Следующая форма позволяет изменить настройки API. |
| `zh_CN` | 您可以通过以下表单编辑 API 设置。 |

### 50. `The following form allows you to request a new key from the API.`

- **Used in:** Integration edit page
- **Usage context:** Intro sentence above the 'request a new API key' form.
- **UI space limit:** No hard limit (intro paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| allows you | – | – | – | vous permet de (verb) | – | – | – | – | – |  |
| api | واجهة برمجة التطبيقات (API) (abbreviation) | – | – | – | – | – | – | – | – |  |
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) |  |
| key | – | – | clave (noun) | – | – | – | – | – | – |  |
| new | – | – | – | Nouveauté (noun) / Nouvelle (expression) / Nouveau (expression) | – | – | – | – | 新增 (verb) | zh_CN glossary '新增' is the 'Add New' action; here the adjective 'new' (新) |
| request | – | – | – | demande (noun) / requête (noun) | – | – | pedido (noun) / solicitar (verb) | – | 要求 (noun) | zh_CN glossary '要求' is a noun; here the verb 'request' (请求) |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | يتيح لك النموذج التالي طلب مفتاح جديد من واجهة برمجة التطبيقات (API). |
| `de_DE` | Mit dem folgenden Formular kannst du einen neuen Schlüssel bei der API anfordern. |
| `es_ES` | El siguiente formulario te permite solicitar una nueva clave a la API. |
| `fr_FR` | Le formulaire suivant vous permet de demander une nouvelle clé à l’API. |
| `it_IT` | Il seguente modulo ti consente di richiedere una nuova chiave all'API. |
| `ja` | 次のフォームで API に新しいキーをリクエストできます。 |
| `pt_PT` | O formulário seguinte permite-lhe solicitar uma nova chave à API. |
| `ru_RU` | Следующая форма позволяет запросить новый ключ у API. |
| `zh_CN` | 您可以通过以下表单向 API 请求新密钥。 |

### 51. `Administrator's Email Address`

- **Used in:** Integration edit page (`email-label`)
- **Usage context:** Label of the email address field used when requesting a new API key from Pdf.Ninja.
- **UI space limit:** English label text measures about 104×35px over 2 lines; its containing header cell measures about 114×69px. The integration table uses content-dependent columns and wrapping, not a fixed two-line limit. The label cell wraps freely and no candidate translation is clipped. The table's overall width (~486-498px) is driven by the 350px `regular-text` input in the adjacent cell, not by the label text; even the English original overflows the 463px form content area. Label choice is therefore governed by glossary/industry/clarity, not by container fit. Full Cyrillic `ru_RU` "Адрес электронной почты администратора" wraps to 4 lines (row ~103px) without clipping.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words). Closest equivalents: WordPress core "Administration Email Address" → `ru_RU` "Адрес электронной почты администрации" (full Cyrillic); CF7 "Email address field" → "Поле адреса email" (Latin retained) and "Please enter an email address" → "Введите адрес электронной почты" (full Cyrillic). Both full-Cyrillic and Latin-"email" forms are attested in official Russian translations; full Cyrillic "электронной почты" is the dominant/preferred form for address labels. The `ru_RU` glossary has no email/address entry.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| administrator | – | Administrator (noun) / Administratoren (noun) | – | administrateur/administratrice (noun) | amministratore (noun) | 管理者 (noun) | – | – | 管理员 (noun) |  |
| email address | – | E-Mail-Adresse (noun) | – | adresse e-mail (noun) | indirizzo email (expression) | – | – | – | – |  |

- `zh_CN`: Glossary 'email' = '邮箱' → '邮箱地址'. 'Email message' is rendered '邮件' (CF7 zh_CN mail-tag = '邮件标签').

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | عنوان البريد الإلكتروني للمسؤول |
| `de_DE` | Admin-E-Mail |
| `es_ES` | Correo electrónico del administrador |
| `fr_FR` | E-mail admin |
| `it_IT` | Email admin |
| `ja` | 管理者メールアドレス |
| `pt_PT` | Email do admin |
| `ru_RU` | Адрес электронной почты администратора |
| `zh_CN` | 管理员邮箱 |

### 52. `Save`

- **Used in:** Integration edit page submit button
- **Usage context:** Submit button that saves the API settings. Same as WP core 'Save'.
- **UI space limit:** BUTTON label, measured 61px wide (h40) for 'Save'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

Exact match in CF7/WP core: de 'Speichern', es 'Guardar', fr 'Enregistrer', it 'Salva', ja '保存', pt 'Guardar', ru 'Сохранить', zh '保存', ar 'حفظ'.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | Contact Form 7 | حفظ |
| `de_DE` | Contact Form 7 | Speichern |
| `es_ES` | Contact Form 7 | Guardar |
| `fr_FR` | Contact Form 7 | Enregistrer |
| `it_IT` | Contact Form 7 | Salva |
| `ja` | Contact Form 7 | 保存 |
| `pt_PT` | Contact Form 7 | Guardar |
| `ru_RU` | Contact Form 7 | Сохранить |
| `zh_CN` | Contact Form 7 | 保存 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| save | – | – | guardar o ahorrar, según contexto (verb) | Enregistrer (verb) | salva (verb) | – | guardar (verb) | – | 保存 (verb) |  |

- `fr_FR`: Glossary 'save' = 'Enregistrer'.

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | حفظ | not measured |
| `de_DE` | Speichern | not measured |
| `es_ES` | Guardar | not measured |
| `fr_FR` | Enregistrer | not measured |
| `it_IT` | Salva | not measured |
| `ja` | 保存 | not measured |
| `pt_PT` | Guardar | not measured |
| `ru_RU` | Сохранить | not measured |
| `zh_CN` | 保存 | not measured |

### 53. `Get New Key`

- **Used in:** Integration edit page submit button
- **Usage context:** Submit button that requests a new API key from the Pdf.Ninja API.
- **UI space limit:** BUTTON label, measured 110px wide (h40) for 'Get New Key'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| key | – | – | clave (noun) | – | – | – | – | – | – |  |
| new | – | – | – | Nouveauté (noun) / Nouvelle (expression) / Nouveau (expression) | – | – | – | – | 新增 (verb) | zh_CN glossary '新增' is the 'Add New' action; here the adjective 'new' (新) |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | الحصول على مفتاح جديد | not measured |
| `de_DE` | Neuen Schlüssel abrufen | not measured |
| `es_ES` | Obtener una nueva clave | not measured |
| `fr_FR` | Obtenir une nouvelle clé | not measured |
| `it_IT` | Ottieni una nuova chiave | not measured |
| `ja` | 新しいキーを取得 | not measured |
| `pt_PT` | Obter nova chave | not measured |
| `ru_RU` | Получить новый ключ | not measured |
| `zh_CN` | 获取新密钥 | not measured |

### 54. `PDF Forms Filler for CF7 plugin error`

- **Used in:** Admin notice label (4 places)
- **Usage context:** Bold label of an admin error notice. Literally 'error of the plugin PDF Forms Filler for CF7'. Use the localized plugin name from #0 as one quoted unit; the translation must not split the name (common mistake: 'PDF Forms Filler for [CF7 plugin error]').
- **UI space limit:** Admin notice bold label; flex, wraps.

**Research / typical translations**

An error-notice label combines the localized plugin name (#0) with the locale’s plugin/error terminology. It is rendered as a bold label followed by a separate message. Because the localized name is descriptive, it is quoted so that it is not read as part of the error phrase.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| error | خطأ (noun) | Fehler (noun) | – | erreur (noun) | errore (noun) | エラー (noun) | – | – | – |  |
| forms | نماذج (noun) | – | – | – | – | – | – | – | – | part of the localized plugin name (#0) |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) | part of the localized plugin name (#0) |
| plugin | إضافة (noun) | Plugin (noun) | plugin (noun) | extension (noun) | plugin (noun) | プラグイン (noun) | plugin (noun) | – | 插件 (noun) |  |

- `ar`: Glossary 'plugin' = 'إضافة'. 'خطأ في إضافة «…»' is rejected: 'إضافة' is also the verbal noun 'adding', so it reads as 'an error in adding «…»'. 'خطأ صادر عن الإضافة «…»' ('error issued by the plugin «…»') is unambiguous and works as the bold notice label before ': {message}'.
- `fr_FR`: Glossary 'plugin' = 'extension'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | خطأ صادر عن الإضافة «أداة تعبئة نماذج PDF لـ CF7» |
| `de_DE` | Fehler des Plugins „PDF-Formularausfüller für CF7“ |
| `es_ES` | Error del plugin «Herramienta de relleno de formularios PDF para CF7» |
| `fr_FR` | Erreur de l’extension «&nbsp;Outil de remplissage de formulaires PDF pour CF7&nbsp;» |
| `it_IT` | Plugin 'Strumento di compilazione moduli PDF per CF7' – Errore |
| `ja` | 「CF7 用 PDF フォーム入力ツール」プラグインのエラー |
| `pt_PT` | Plugin 'Ferramenta de preenchimento de formulários PDF para CF7' – Erro |
| `ru_RU` | Ошибка плагина «Инструмент заполнения PDF-форм для CF7» |
| `zh_CN` | “适用于 CF7 的 PDF 表单填写工具”插件错误 |

### 55. `Can't save new key.`

- **Used in:** Integration edit page error notice
- **Usage context:** Error message: the newly requested API key could not be saved.
- **UI space limit:** No hard limit (error notice; wraps).

**Research / typical translations**

This is a failure to persist a new API key. Keep its negative polarity and distinguish it from Settings saved, which reports successful persistence.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| key | – | – | clave (noun) | – | – | – | – | – | – |  |
| new | – | – | – | Nouveauté (noun) / Nouvelle (expression) / Nouveau (expression) | – | – | – | – | 新增 (verb) | zh_CN glossary '新增' is the 'Add New' action; here the adjective 'new' (新) |
| save | – | – | guardar o ahorrar, según contexto (verb) | Enregistrer (verb) | salva (verb) | – | guardar (verb) | – | 保存 (verb) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تعذّر حفظ المفتاح الجديد. |
| `de_DE` | Der neue Schlüssel kann nicht gespeichert werden. |
| `es_ES` | No se puede guardar la nueva clave. |
| `fr_FR` | Impossible d’enregistrer la nouvelle clé. |
| `it_IT` | Impossibile salvare la nuova chiave. |
| `ja` | 新しいキーを保存できません。 |
| `pt_PT` | Não é possível guardar a nova chave. |
| `ru_RU` | Не удалось сохранить новый ключ. |
| `zh_CN` | 无法保存新密钥。 |

### 56. `Settings saved.`

- **Used in:** Integration edit page success notice
- **Usage context:** Success message after saving the API settings. Same as WP core 'Settings saved.'.
- **UI space limit:** No hard limit (success notice; wraps).

**Research / typical translations**

Exact match in CF7/WP core: ar 'تمّ حفظ الإعدادات.', de 'Die Einstellungen wurden gespeichert.', es 'Ajustes guardados.', fr 'Réglages enregistrés.', it 'Impostazioni salvate.', ja '設定を保存しました。', pt (core) 'Opções guardadas.', ru 'Настройки сохранены.', zh '设置已保存。'

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | Contact Form 7 | تمّ حفظ الإعدادات. |
| `de_DE` | Contact Form 7 | Die Einstellungen wurden gespeichert. |
| `es_ES` | Contact Form 7 | Ajustes guardados. |
| `fr_FR` | Contact Form 7 | Réglages enregistrés. |
| `it_IT` | Contact Form 7 | Impostazioni salvate. |
| `ja` | Contact Form 7 | 設定を保存しました。 |
| `pt_PT` | Contact Form 7 | Definições guardadas. |
| `ru_RU` | Contact Form 7 | Настройки сохранены. |
| `zh_CN` | Contact Form 7 | 设置已保存。 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| saved | – | – | – | – | – | – | guardado(a) (adjective) | – | – |  |
| settings | إعدادات (noun) | – | ajustes (noun) | Réglages (noun) | – | – | opções (noun) | настройки (noun) | – |  |

- `es_ES`: Glossary 'settings' = 'ajustes'.
- `fr_FR`: Glossary 'settings' = 'Réglages'.
- `pt_PT`: Glossary 'settings' = 'opções'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تمّ حفظ الإعدادات. |
| `de_DE` | Die Einstellungen wurden gespeichert. |
| `es_ES` | Ajustes guardados. |
| `fr_FR` | Réglages enregistrés. |
| `it_IT` | Impostazioni salvate. |
| `ja` | 設定を保存しました。 |
| `pt_PT` | Opções guardadas. |
| `ru_RU` | Настройки сохранены. |
| `zh_CN` | 设置已保存。 |

### 57. `Failed to acquire the Pdf.Ninja API key on last attempt. {a-href-edit-service-page}Please retry manually{/a}.`

- **Used in:** Admin error notice (`admin_notices`)
- **Usage context:** Shown when automatic API key acquisition failed. {a-href-edit-service-page}...{/a} links to the Integration edit page where the user can retry.
- **UI space limit:** No hard limit (error notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| api key | – | – | – | Clé de l’API (noun) | – | – | – | – | – |  |
| last | – | – | – | Dernier (noun) | durare (verb) | – | – | – | – | it_IT glossary 'durare' is the verb 'to last'; here 'last attempt' (adjective) |
| on | – | – | – | – | on (noun) | – | – | – | – | it_IT glossary 'on' is the switch state; here a preposition |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) | only part of a product name / URL / placeholder – not translated |
| please | – | – | – | Veuillez (noun) | NON SI TRADUCE (noun) | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | فشل الحصول على مفتاح واجهة برمجة التطبيقات (API) لـ Pdf.Ninja في المحاولة الأخيرة. {a-href-edit-service-page}يُرجى إعادة المحاولة يدويًا{/a}. |
| `de_DE` | Der Pdf.Ninja-API-Schlüssel konnte beim letzten Versuch nicht abgerufen werden. {a-href-edit-service-page}Bitte versuche es manuell erneut{/a}. |
| `es_ES` | No se ha podido obtener la clave de la API de Pdf.Ninja en el último intento. {a-href-edit-service-page}Vuelve a intentarlo manualmente{/a}. |
| `fr_FR` | Échec de l’obtention de la clé de l’API Pdf.Ninja lors de la dernière tentative. {a-href-edit-service-page}Veuillez réessayer manuellement{/a}. |
| `it_IT` | Impossibile ottenere la chiave API di Pdf.Ninja all'ultimo tentativo. {a-href-edit-service-page}Riprova manualmente{/a}. |
| `ja` | 前回の試行で Pdf.Ninja API キーを取得できませんでした。{a-href-edit-service-page}手動で再試行してください{/a}。 |
| `pt_PT` | Falha ao obter a chave da API Pdf.Ninja na última tentativa. {a-href-edit-service-page}Por favor, tente novamente manualmente{/a}. |
| `ru_RU` | Не удалось получить ключ API Pdf.Ninja при последней попытке. {a-href-edit-service-page}Повторите попытку вручную{/a}. |
| `zh_CN` | 上次尝试获取 Pdf.Ninja API 密钥失败。{a-href-edit-service-page}请手动重试{/a}。 |

### 58. `PDF Forms Filler for CF7 plugin warning`

- **Used in:** Admin notice label (2 places)
- **Usage context:** Bold label of an admin warning notice. Same structure as #54 (quoted localized plugin name from #0, not split).
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| forms | نماذج (noun) | – | – | – | – | – | – | – | – | part of the localized plugin name (#0) |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) | part of the localized plugin name (#0) |
| plugin | إضافة (noun) | Plugin (noun) | plugin (noun) | extension (noun) | plugin (noun) | プラグイン (noun) | plugin (noun) | – | 插件 (noun) |  |
| warning | – | – | advertencia (noun) | avertissement (noun) | attenzione (noun) | – | atenção (noun) | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تحذير صادر عن الإضافة «أداة تعبئة نماذج PDF لـ CF7» |
| `de_DE` | Warnung des Plugins „PDF-Formularausfüller für CF7“ |
| `es_ES` | Advertencia del plugin «Herramienta de relleno de formularios PDF para CF7» |
| `fr_FR` | Avertissement de l’extension «&nbsp;Outil de remplissage de formulaires PDF pour CF7&nbsp;» |
| `it_IT` | Plugin 'Strumento di compilazione moduli PDF per CF7' – Attenzione |
| `ja` | 「CF7 用 PDF フォーム入力ツール」プラグインの警告 |
| `pt_PT` | Plugin 'Ferramenta de preenchimento de formulários PDF para CF7' – Atenção |
| `ru_RU` | Предупреждение плагина «Инструмент заполнения PDF-форм для CF7» |
| `zh_CN` | “适用于 CF7 的 PDF 表单填写工具”插件警告 |

### 59. `Your Contact Form 7 integration settings indicate that you are using an insecure connection to the Pdf.Ninja API server.`

- **Used in:** Admin warning notice
- **Usage context:** Warning shown when the Integration settings use plain HTTP or disabled certificate verification.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| api | واجهة برمجة التطبيقات (API) (abbreviation) | – | – | – | – | – | – | – | – |  |
| connection | – | – | – | – | – | – | ligação (noun) | – | – |  |
| contact form | – | – | – | – | – | お問い合わせフォーム (noun) | – | – | – | only part of a product name / URL / placeholder – not translated |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) | only part of a product name / URL / placeholder – not translated |
| server | خادم (noun) | Server (noun) | – | serveur (noun) | server (noun) | サーバー (noun) | – | – | 服务器 (noun) |  |
| settings | إعدادات (noun) | – | ajustes (noun) | Réglages (noun) | – | – | opções (noun) | настройки (noun) | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تشير إعدادات التكامل في Contact Form 7 إلى أنك تستخدم اتصالًا غير آمن بخادم واجهة برمجة التطبيقات (API) لـ Pdf.Ninja. |
| `de_DE` | Deine Contact-Form-7-Integrationseinstellungen zeigen, dass du eine unsichere Verbindung zum Pdf.Ninja-API-Server verwendest. |
| `es_ES` | Los ajustes de integración de Contact Form 7 indican que estás usando una conexión no segura con el servidor de la API de Pdf.Ninja. |
| `fr_FR` | Vos réglages d’intégration de Contact Form 7 indiquent que vous utilisez une connexion non sécurisée au serveur de l’API Pdf.Ninja. |
| `it_IT` | Le impostazioni di integrazione di Contact Form 7 indicano che stai utilizzando una connessione non sicura al server API di Pdf.Ninja. |
| `ja` | Contact Form 7 のインテグレーション設定で、Pdf.Ninja API サーバーへの安全でない接続が使用されています。 |
| `pt_PT` | As opções de integração do Contact Form 7 indicam que está a utilizar uma ligação não segura ao servidor da API Pdf.Ninja. |
| `ru_RU` | Настройки интеграции Contact Form 7 указывают на то, что вы используете небезопасное соединение с сервером API Pdf.Ninja. |
| `zh_CN` | 您的 Contact Form 7 集成设置表明您正在使用与 Pdf.Ninja API 服务器的不安全连接。 |

### 60. `Unknown error`

- **Used in:** JS `__Unknown_error`; `WPCF7_Pdf_Ninja` / `admin_notices`
- **Usage context:** Generic fallback error message when no specific error message is available.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WooCommerce | خطأ غير معروف |
| `de_DE` | WooCommerce | Unbekannter Fehler |
| `es_ES` | WooCommerce | Error desconocido |
| `fr_FR` | WooCommerce | Erreur inconnue |
| `it_IT` | WooCommerce | Errore sconosciuto |
| `ja` | WooCommerce | 不明なエラー |
| `pt_PT` | WooCommerce | Erro desconhecido |
| `ru_RU` | WooCommerce | Неизвестная ошибка |
| `zh_CN` | WooCommerce | 未知错误 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| error | خطأ (noun) | Fehler (noun) | – | erreur (noun) | errore (noun) | エラー (noun) | – | – | – |  |
| unknown | غير معروف (adjective) | – | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | خطأ غير معروف |
| `de_DE` | Unbekannter Fehler |
| `es_ES` | Error desconocido |
| `fr_FR` | Erreur inconnue |
| `it_IT` | Errore sconosciuto |
| `ja` | 不明なエラー |
| `pt_PT` | Erro desconhecido |
| `ru_RU` | Неизвестная ошибка |
| `zh_CN` | 未知错误 |

### 61. `Missing feature`

- **Used in:** modules/service.php (base class methods)
- **Usage context:** Exception message thrown when the active PDF service does not implement a feature (fill, image, info, ...).
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| feature | – | Funktion (noun) / hervorheben (verb) | característica (noun) / destacar (verb) | fonctionnalité (noun) | caratteristica (noun) | – | recurso (ou funcionalidade) (noun) | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | ميزة مفقودة |
| `de_DE` | Fehlende Funktion |
| `es_ES` | Falta una característica |
| `fr_FR` | Fonctionnalité manquante |
| `it_IT` | Caratteristica mancante |
| `ja` | 機能が不足しています |
| `pt_PT` | Funcionalidade em falta |
| `ru_RU` | Функция отсутствует |
| `zh_CN` | 缺少功能 |

### 63. `Docs`

- **Used in:** Plugins list row meta link (`plugin_row_meta`)
- **Usage context:** Short link to the documentation site. Same as CF7 'Docs'.
- **UI space limit:** Action link in the Plugins list row, measured 29px wide (h14) for 'Docs'. Reference text width only; action links grow and their list can wrap. No fixed character-count maximum is established.

**Research / typical translations**

CF7 translates its own 'Docs' link as de 'Dokumente', es 'Documentación', fr 'Documentation', it 'Documentazione', ja '使い方', pt 'Documentação', ru 'Документация', zh '文档', ar 'المستندات'. German uses 'Dokumentation' (the WordPress core term for documentation) because 'Dokumente' means 'documents'.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | Contact Form 7 | المستندات |
| `de_DE` | Contact Form 7 | Dokumente |
| `es_ES` | Contact Form 7 | Documentación |
| `fr_FR` | Contact Form 7 | Documentation |
| `it_IT` | Contact Form 7 | Documentazione |
| `ja` | Contact Form 7 | 使い方 |
| `pt_PT` | Contact Form 7 | Documentação |
| `ru_RU` | Contact Form 7 | Документация |
| `zh_CN` | Contact Form 7 | 文档 |

**WordPress glossary terms**

No glossary term occurs in this string.

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | المستندات | not measured |
| `de_DE` | Dokumentation | not measured |
| `es_ES` | Documentación | not measured |
| `fr_FR` | Documentation | not measured |
| `it_IT` | Documentazione | not measured |
| `ja` | ドキュメンテーション | not measured |
| `pt_PT` | Documentação | not measured |
| `ru_RU` | Документация | not measured |
| `zh_CN` | 文档 | not measured |

### 64. `Support`

- **Used in:** Plugins list row meta link
- **Usage context:** Short link to the WordPress.org support forum. Same as CF7 'Support'.
- **UI space limit:** Action link in the Plugins list row, measured 48px wide (h14) for 'Support'. Reference text width only; action links grow and their list can wrap. No fixed character-count maximum is established.

**Research / typical translations**

CF7: de/fr 'Support', es 'Soporte', it 'Supporto', ja 'サポート', pt 'Suporte', ru 'Поддержка', zh '支持', ar 'الدعم الفني'.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | Contact Form 7 | الدعم الفني |
| `de_DE` | Contact Form 7 | Support |
| `es_ES` | Contact Form 7 | Soporte |
| `fr_FR` | Contact Form 7 | Support |
| `it_IT` | Contact Form 7 | Supporto |
| `ja` | Contact Form 7 | サポート |
| `pt_PT` | Contact Form 7 | Suporte |
| `ru_RU` | Contact Form 7 | Поддержка |
| `zh_CN` | Contact Form 7 | 支持 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| support | – | – | es compatible con / dar soporte (verb) | support (noun) / prendre en charge (verb) | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | الدعم |
| `de_DE` | Support |
| `es_ES` | Soporte |
| `fr_FR` | Support |
| `it_IT` | Supporto |
| `ja` | サポート |
| `pt_PT` | Suporte |
| `ru_RU` | Поддержка |
| `zh_CN` | 支持 |

### 65. `The required plugin 'Contact Form 7' version is not installed!`

- **Used in:** Admin error notice
- **Usage context:** Shown when Contact Form 7 is not installed/active or its version does not meet the plugin's requirements. 'Contact Form 7' is a product name and must not be translated.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| contact form | – | – | – | – | – | お問い合わせフォーム (noun) | – | – | – | only part of a product name / URL / placeholder – not translated |
| required plugin | – | – | – | – | plugin richiesto (expression) | – | – | – | – |  |
| version | نسخة (noun) | – | – | – | – | – | – | – | – |  |

- `it_IT`: Glossary 'required plugin' = 'plugin richiesto'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | النسخة المطلوبة من الإضافة «Contact Form 7» غير مثبّتة! |
| `de_DE` | Die erforderliche Version des Plugins „Contact Form 7“ ist nicht installiert! |
| `es_ES` | ¡La versión requerida del plugin «Contact Form 7» no está instalada! |
| `fr_FR` | La version requise de l’extension «&nbsp;Contact Form 7&nbsp;» n’est pas installée&nbsp;! |
| `it_IT` | La versione necessaria del plugin richiesto 'Contact Form 7' non è installata! |
| `ja` | 必要なプラグイン「Contact Form 7」のバージョンがインストールされていません ! |
| `pt_PT` | A versão necessária do plugin 'Contact Form 7' não está instalada! |
| `ru_RU` | Требуемая версия плагина «Contact Form 7» не установлена! |
| `zh_CN` | 未安装所需版本的“Contact Form 7”插件！ |

### 66. `The currently installed version of 'Contact Form 7' plugin ({current-wpcf7-version}) may not be supported by the current version of 'PDF Forms Filler for CF7' plugin ({current-plugin-version}), please switch to 'Contact Form 7' plugin version {supported-wpcf7-version} or below to ensure that 'PDF Forms Filler for CF7' plugin would work correctly.`

- **Used in:** Admin warning notice
- **Usage context:** Shown when the installed CF7 version is newer than the tested one. {current-wpcf7-version}, {current-plugin-version}, {supported-wpcf7-version} are version numbers. 'Contact Form 7' stays untranslated; 'PDF Forms Filler for CF7' uses the localized plugin name from #0. Both names are quoted with the locale’s quotation marks.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| contact form | – | – | – | – | – | お問い合わせフォーム (noun) | – | – | – | only part of a product name / URL / placeholder – not translated |
| current | – | – | – | – | – | – | – | – | 当前 (adjective) |  |
| forms | نماذج (noun) | – | – | – | – | – | – | – | – | part of the localized plugin name (#0) |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) | part of the localized plugin name (#0) |
| please | – | – | – | Veuillez (noun) | NON SI TRADUCE (noun) | – | – | – | – |  |
| plugin | إضافة (noun) | Plugin (noun) | plugin (noun) | extension (noun) | plugin (noun) | プラグイン (noun) | plugin (noun) | – | 插件 (noun) |  |
| supported | – | – | compatible (noun) | – | – | – | – | – | – |  |
| switch | – | – | – | – | – | – | mudar (verb) | – | 切换 (verb) |  |
| version | نسخة (noun) | – | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | قد لا تكون النسخة المثبّتة حاليًا من الإضافة «Contact Form 7» ({current-wpcf7-version}) مدعومة من قِبل النسخة الحالية من الإضافة «أداة تعبئة نماذج PDF لـ CF7» ({current-plugin-version})، يُرجى التبديل إلى نسخة الإضافة «Contact Form 7» {supported-wpcf7-version} أو أقدم لضمان عمل الإضافة «أداة تعبئة نماذج PDF لـ CF7» بشكل صحيح. |
| `de_DE` | Die derzeit installierte Version des Plugins „Contact Form 7“ ({current-wpcf7-version}) wird von der aktuellen Version des Plugins „PDF-Formularausfüller für CF7“ ({current-plugin-version}) möglicherweise nicht unterstützt. Bitte wechsle zur Version {supported-wpcf7-version} oder niedriger des Plugins „Contact Form 7“, um sicherzustellen, dass das Plugin „PDF-Formularausfüller für CF7“ korrekt funktioniert. |
| `es_ES` | Es posible que la versión instalada actualmente del plugin «Contact Form 7» ({current-wpcf7-version}) no sea compatible con la versión actual del plugin «Herramienta de relleno de formularios PDF para CF7» ({current-plugin-version}). Cambia a la versión {supported-wpcf7-version} o anterior del plugin «Contact Form 7» para asegurarte de que el plugin «Herramienta de relleno de formularios PDF para CF7» funcione correctamente. |
| `fr_FR` | La version actuellement installée de l’extension «&nbsp;Contact Form 7&nbsp;» ({current-wpcf7-version}) n’est peut-être pas prise en charge par la version actuelle de l’extension «&nbsp;Outil de remplissage de formulaires PDF pour CF7&nbsp;» ({current-plugin-version}). Veuillez passer à la version {supported-wpcf7-version} ou antérieure de l’extension «&nbsp;Contact Form 7&nbsp;» pour garantir le bon fonctionnement de l’extension «&nbsp;Outil de remplissage de formulaires PDF pour CF7&nbsp;». |
| `it_IT` | La versione attualmente installata del plugin 'Contact Form 7' ({current-wpcf7-version}) potrebbe non essere supportata dalla versione attuale del plugin 'Strumento di compilazione moduli PDF per CF7' ({current-plugin-version}); passa alla versione {supported-wpcf7-version} o precedente del plugin 'Contact Form 7' per garantire il corretto funzionamento del plugin 'Strumento di compilazione moduli PDF per CF7'. |
| `ja` | 現在インストールされている「Contact Form 7」プラグイン ({current-wpcf7-version}) は、現在の「CF7 用 PDF フォーム入力ツール」プラグイン ({current-plugin-version}) でサポートされていない可能性があります。「CF7 用 PDF フォーム入力ツール」プラグインを正しく動作させるため、「Contact Form 7」プラグインをバージョン {supported-wpcf7-version} 以下に切り替えてください。 |
| `pt_PT` | A versão actualmente instalada do plugin 'Contact Form 7' ({current-wpcf7-version}) pode não ser suportada pela versão actual do plugin 'Ferramenta de preenchimento de formulários PDF para CF7' ({current-plugin-version}). Por favor, mude para a versão {supported-wpcf7-version} ou inferior do plugin 'Contact Form 7' para garantir que o plugin 'Ferramenta de preenchimento de formulários PDF para CF7' funciona correctamente. |
| `ru_RU` | Установленная версия плагина «Contact Form 7» ({current-wpcf7-version}) может не поддерживаться текущей версией плагина «Инструмент заполнения PDF-форм для CF7» ({current-plugin-version}). Пожалуйста, перейдите на версию плагина «Contact Form 7» {supported-wpcf7-version} или ниже, чтобы плагин «Инструмент заполнения PDF-форм для CF7» работал корректно. |
| `zh_CN` | 当前安装的“Contact Form 7”插件版本（{current-wpcf7-version}）可能不受当前版本的“适用于 CF7 的 PDF 表单填写工具”插件（{current-plugin-version}）支持，请切换到“Contact Form 7”插件 {supported-wpcf7-version} 或更低版本，以确保“适用于 CF7 的 PDF 表单填写工具”插件正常工作。 |

### 67. `Unknown version`

- **Used in:** Substituted into {current-wpcf7-version} of #66
- **Usage context:** Used when the CF7 version constant is not defined.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| unknown | غير معروف (adjective) | – | – | – | – | – | – | – | – |  |
| version | نسخة (noun) | – | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | نسخة غير معروفة |
| `de_DE` | Unbekannte Version |
| `es_ES` | Versión desconocida |
| `fr_FR` | Version inconnue |
| `it_IT` | Versione sconosciuta |
| `ja` | 不明なバージョン |
| `pt_PT` | Versão desconhecida |
| `ru_RU` | Неизвестная версия |
| `zh_CN` | 未知版本 |

### 68. `Are you sure you want to delete this file?  This will delete field mappings and image embeds associated with this file.`

- **Used in:** JS confirm() dialog (delete attachment)
- **Usage context:** Confirmation before removing an attached PDF file from the contact form; field mappings and image embeds for that file are deleted too.
- **UI space limit:** JS confirm() dialog text; no hard limit (browser dialog wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| are you sure you want | – | – | – | – | vuoi davvero (expression) | – | Tem a certeza que pretende (expression) | – | – |  |
| delete | حذف (verb) | löschen (verb) / lösche (verb) / gelöscht (verb) | borrar (verb) | – | elimina (verb) | – | eliminar (ou excluir) (verb) | – | 删除 (verb) |  |
| embeds | تضمينات (noun) | – | – | – | – | – | – | – | – |  |
| field | – | – | – | – | – | – | – | – | 字段 (noun) |  |
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| image | – | – | – | – | immagine (noun) | – | – | – | 图片 (noun) |  |

- `it_IT`: Glossary 'are you sure you want' = 'vuoi davvero'.
- `pt_PT`: Glossary 'are you sure you want' = 'Tem a certeza que pretende'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | هل أنت متأكد من أنك تريد حذف هذا الملف؟ سيؤدي ذلك إلى حذف تعيينات الحقول وتضمينات الصور المرتبطة بهذا الملف. |
| `de_DE` | Möchtest du diese Datei wirklich löschen? Dadurch werden auch die Feldzuordnungen und Bildeinbettungen gelöscht, die mit dieser Datei verknüpft sind. |
| `es_ES` | ¿Seguro que quieres borrar este archivo? Esto borrará las asignaciones de campos y las imágenes incrustadas asociadas a este archivo. |
| `fr_FR` | Voulez-vous vraiment supprimer ce fichier&nbsp;? Cela supprimera les correspondances de champs et les images embarquées associées à ce fichier. |
| `it_IT` | Vuoi davvero eliminare questo file? Verranno eliminate anche le mappature dei campi e le immagini incorporate associate a questo file. |
| `ja` | このファイルを削除してもよいですか ? このファイルに関連付けられたフィールドマッピングと画像の埋め込みも削除されます。 |
| `pt_PT` | Tem a certeza que pretende eliminar este ficheiro? Isto irá eliminar os mapeamentos de campos e as imagens incorporadas associados a este ficheiro. |
| `ru_RU` | Вы действительно хотите удалить этот файл? Это приведёт к удалению сопоставлений полей и встроенных изображений, связанных с этим файлом. |
| `zh_CN` | 确定要删除此文件吗？这也将删除与此文件关联的字段映射和图片嵌入。 |

### 69. `Are you sure you want to delete this mapping?`

- **Used in:** JS confirm() dialog
- **Usage context:** Confirmation before deleting one CF7-to-PDF field mapping.
- **UI space limit:** JS confirm() dialog text; no hard limit.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| are you sure you want | – | – | – | – | vuoi davvero (expression) | – | Tem a certeza que pretende (expression) | – | – |  |
| delete | حذف (verb) | löschen (verb) / lösche (verb) / gelöscht (verb) | borrar (verb) | – | elimina (verb) | – | eliminar (ou excluir) (verb) | – | 删除 (verb) |  |
| mapping | – | – | asignación (expression) | – | – | – | mapeamento (noun) / mapear (verb) | – | 映射 (adjective) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | هل أنت متأكد من أنك تريد حذف هذا التعيين؟ |
| `de_DE` | Möchtest du diese Zuordnung wirklich löschen? |
| `es_ES` | ¿Seguro que quieres borrar esta asignación? |
| `fr_FR` | Voulez-vous vraiment supprimer cette correspondance&nbsp;? |
| `it_IT` | Vuoi davvero eliminare questa mappatura? |
| `ja` | このマッピングを削除してもよいですか ? |
| `pt_PT` | Tem a certeza que pretende eliminar este mapeamento? |
| `ru_RU` | Вы действительно хотите удалить это сопоставление? |
| `zh_CN` | 确定要删除此映射吗？ |

### 70. `Are you sure you want to delete all mappings?`

- **Used in:** JS confirm() dialog
- **Usage context:** Confirmation before deleting all field mappings.
- **UI space limit:** JS confirm() dialog text; no hard limit.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| all | – | – | – | – | – | すべて (adjective) | – | – | – |  |
| are you sure you want | – | – | – | – | vuoi davvero (expression) | – | Tem a certeza que pretende (expression) | – | – |  |
| delete | حذف (verb) | löschen (verb) / lösche (verb) / gelöscht (verb) | borrar (verb) | – | elimina (verb) | – | eliminar (ou excluir) (verb) | – | 删除 (verb) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | هل أنت متأكد من أنك تريد حذف جميع التعيينات؟ |
| `de_DE` | Möchtest du wirklich alle Zuordnungen löschen? |
| `es_ES` | ¿Seguro que quieres borrar todas las asignaciones? |
| `fr_FR` | Voulez-vous vraiment supprimer toutes les correspondances&nbsp;? |
| `it_IT` | Vuoi davvero eliminare tutte le mappature? |
| `ja` | すべてのマッピングを削除してもよいですか ? |
| `pt_PT` | Tem a certeza que pretende eliminar todos os mapeamentos? |
| `ru_RU` | Вы действительно хотите удалить все сопоставления? |
| `zh_CN` | 确定要删除所有映射吗？ |

### 71. `Are you sure you want to delete all value mappings?`

- **Used in:** JS confirm() dialog
- **Usage context:** Confirmation before deleting all value mappings of a field mapping (CF7 value -> PDF value replacements).
- **UI space limit:** JS confirm() dialog text; no hard limit.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| all | – | – | – | – | – | すべて (adjective) | – | – | – |  |
| are you sure you want | – | – | – | – | vuoi davvero (expression) | – | Tem a certeza que pretende (expression) | – | – |  |
| delete | حذف (verb) | löschen (verb) / lösche (verb) / gelöscht (verb) | borrar (verb) | – | elimina (verb) | – | eliminar (ou excluir) (verb) | – | 删除 (verb) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | هل أنت متأكد من أنك تريد حذف جميع تعيينات القيم؟ |
| `de_DE` | Möchtest du wirklich alle Wertzuordnungen löschen? |
| `es_ES` | ¿Seguro que quieres borrar todas las asignaciones de valores? |
| `fr_FR` | Voulez-vous vraiment supprimer toutes les correspondances de valeurs&nbsp;? |
| `it_IT` | Vuoi davvero eliminare tutte le mappature dei valori? |
| `ja` | すべての値マッピングを削除してもよいですか ? |
| `pt_PT` | Tem a certeza que pretende eliminar todos os mapeamentos de valores? |
| `ru_RU` | Вы действительно хотите удалить все сопоставления значений? |
| `zh_CN` | 确定要删除所有值映射吗？ |

### 72. `Are you sure you want to attach a PDF file without any form fields?`

- **Used in:** JS confirm() dialog
- **Usage context:** Confirmation when the selected PDF has no form fields.
- **UI space limit:** JS confirm() dialog text; no hard limit.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| are you sure you want | – | – | – | – | vuoi davvero (expression) | – | Tem a certeza que pretende (expression) | – | – |  |
| fields | حقول (noun) | – | – | – | – | – | – | – | – |  |
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | هل أنت متأكد من أنك تريد إرفاق ملف PDF لا يحتوي على أي حقول نموذج؟ |
| `de_DE` | Möchtest du wirklich eine PDF-Datei ohne Formularfelder anhängen? |
| `es_ES` | ¿Seguro que quieres adjuntar un archivo PDF sin campos de formulario? |
| `fr_FR` | Voulez-vous vraiment joindre un fichier PDF sans aucun champ de formulaire&nbsp;? |
| `it_IT` | Vuoi davvero allegare un file PDF senza campi del modulo? |
| `ja` | フォームフィールドのない PDF ファイルを添付してもよいですか ? |
| `pt_PT` | Tem a certeza que pretende anexar um ficheiro PDF sem campos de formulário? |
| `ru_RU` | Вы действительно хотите прикрепить PDF-файл без полей формы? |
| `zh_CN` | 确定要附加不含任何表单字段的 PDF 文件吗？ |

### 73. `Are you sure you want to delete this embeded image?`

- **Used in:** JS confirm() dialog
- **Usage context:** Confirmation before deleting an image embed. ('embeded' is a typo for 'embedded' in the source.)
- **UI space limit:** JS confirm() dialog text; no hard limit.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| are you sure you want | – | – | – | – | vuoi davvero (expression) | – | Tem a certeza que pretende (expression) | – | – |  |
| delete | حذف (verb) | löschen (verb) / lösche (verb) / gelöscht (verb) | borrar (verb) | – | elimina (verb) | – | eliminar (ou excluir) (verb) | – | 删除 (verb) |  |
| image | – | – | – | – | immagine (noun) | – | – | – | 图片 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | هل أنت متأكد من أنك تريد حذف هذه الصورة المضمّنة؟ |
| `de_DE` | Möchtest du dieses eingebettete Bild wirklich löschen? |
| `es_ES` | ¿Seguro que quieres borrar esta imagen incrustada? |
| `fr_FR` | Voulez-vous vraiment supprimer cette image embarquée&nbsp;? |
| `it_IT` | Vuoi davvero eliminare questa immagine incorporata? |
| `ja` | この埋め込み画像を削除してもよいですか ? |
| `pt_PT` | Tem a certeza que pretende eliminar esta imagem incorporada? |
| `ru_RU` | Вы действительно хотите удалить это встроенное изображение? |
| `zh_CN` | 确定要删除此嵌入的图片吗？ |

### 74. `Show Help`

- **Used in:** Help toggle link (JS + templates)
- **Usage context:** Link that shows the help box of a tool.
- **UI space limit:** English control measures about 73×18px, including padding; it floats beside a heading within the containing tool row, not in a fixed 73px slot. Actual fit depends on the combined heading/link widths. Tested locale captions fit at the reference viewport.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| help | – | – | – | aide (noun) | – | – | – | – | – |  |
| show | – | – | – | afficher (verb) | – | – | mostrar (verb) | – | – |  |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | إظهار المساعدة | fits, ~73px control |
| `de_DE` | Hilfe anzeigen | fits, ~94px control |
| `es_ES` | Mostrar ayuda | fits, ~95px control |
| `fr_FR` | Afficher l’aide | fits, ~92px control |
| `it_IT` | Mostra aiuto | fits, ~85px control |
| `ja` | ヘルプを表示 | fits, ~89px control |
| `pt_PT` | Mostrar ajuda | fits, ~92px control |
| `ru_RU` | Показать справку | fits, ~118px control |
| `zh_CN` | 显示帮助 | fits, ~62px control |

### 75. `Hide Help`

- **Used in:** Help toggle link (JS + templates)
- **Usage context:** Link that hides the help box of a tool.
- **UI space limit:** Content-driven help toggle in the same tool row as Show Help. Its translated width is not fixed to the English show caption; evaluate it beside the heading and other controls.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| help | – | – | – | aide (noun) | – | – | – | – | – |  |
| hide | – | – | ocultar (verb) | masquer (verb) | – | 非表示 (verb) | – | – | 隐藏 (verb) |  |

- `ja`: Glossary 'hide' = '非表示'.

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | إخفاء المساعدة | not measured |
| `de_DE` | Hilfe ausblenden | not measured |
| `es_ES` | Ocultar ayuda | not measured |
| `fr_FR` | Masquer l’aide | not measured |
| `it_IT` | Nascondi aiuto | not measured |
| `ja` | ヘルプを非表示 | not measured |
| `pt_PT` | Esconder ajuda | not measured |
| `ru_RU` | Скрыть справку | not measured |
| `zh_CN` | 隐藏帮助 | not measured |

### 76. `Show Tag Generator`

- **Used in:** Tag generator toggle link
- **Usage context:** Link that expands the deprecated tag generator tool. 'Tag Generator' refers to the CF7 form-tag generator.
- **UI space limit:** English caption measures about 119×14px, but the dialog row provides 720px, not a fixed 119px slot. Measure heading, icon, toggle and help control together. At the reference viewport the complete expanded row fits for all supported locales; the Portuguese heading/toggle/help controls measure about 275/253/92px.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| generator | – | – | – | – | – | – | – | – | 生成器 (noun) |  |
| show | – | – | – | afficher (verb) | – | – | mostrar (verb) | – | – |  |
| tag | وسم (noun) | Schlagwort (noun) / Tag (noun) | etiqueta (noun) | étiquette (noun) / balise (noun) | tag (noun) / tagga (verb) | タグ (noun) | elemento (noun) / etiqueta (noun) | метка (noun) | 标签 (noun) / 标记 (verb) |  |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | إظهار مولّد الوسوم | not measured |
| `de_DE` | Tag-Generator anzeigen | not measured |
| `es_ES` | Mostrar generador de etiquetas | not measured |
| `fr_FR` | Afficher le générateur de balises | not measured |
| `it_IT` | Mostra generatore di tag | not measured |
| `ja` | タグジェネレーターを表示 | not measured |
| `pt_PT` | Mostrar gerador de etiquetas de variáveis | not measured |
| `ru_RU` | Показать генератор тегов | not measured |
| `zh_CN` | 显示标签生成器 | not measured |

### 77. `Hide Tag Generator`

- **Used in:** Tag generator toggle link
- **Usage context:** Link that collapses the deprecated tag generator tool.
- **UI space limit:** Content-driven caption in the same 720px dialog row as Show Tag Generator. Tested full hide captions plus headings fit at the reference viewport; there is no observed 20-character maximum. Check other controls in the expanded state and narrower layouts separately.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| generator | – | – | – | – | – | – | – | – | 生成器 (noun) |  |
| hide | – | – | ocultar (verb) | masquer (verb) | – | 非表示 (verb) | – | – | 隐藏 (verb) |  |
| tag | وسم (noun) | Schlagwort (noun) / Tag (noun) | etiqueta (noun) | étiquette (noun) / balise (noun) | tag (noun) / tagga (verb) | タグ (noun) | elemento (noun) / etiqueta (noun) | метка (noun) | 标签 (noun) / 标记 (verb) |  |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | إخفاء مولّد الوسوم | not measured |
| `de_DE` | Tag-Generator ausblenden | not measured |
| `es_ES` | Ocultar generador de etiquetas | not measured |
| `fr_FR` | Masquer le générateur de balises | not measured |
| `it_IT` | Nascondi generatore di tag | not measured |
| `ja` | タグジェネレーターを非表示 | not measured |
| `pt_PT` | Esconder gerador de etiquetas de variáveis | not measured |
| `ru_RU` | Скрыть генератор тегов | not measured |
| `zh_CN` | 隐藏标签生成器 | not measured |

### 78. `Select a PDF file`

- **Used in:** JS `wp.media` frame title
- **Usage context:** Title of the WordPress media library modal used to choose a PDF file to attach.
- **UI space limit:** wp.media frame title; no hard limit (media modal title bar, wraps/truncates ~40 chars).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |
| select | – | – | – | liste déroulante (noun) / Sélectionner (verb) | – | – | selector (noun) | – | 选择 (verb) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | حدد ملف PDF |
| `de_DE` | Wähle eine PDF-Datei aus |
| `es_ES` | Selecciona un archivo PDF |
| `fr_FR` | Sélectionnez un fichier PDF |
| `it_IT` | Seleziona un file PDF |
| `ja` | PDF ファイルを選択 |
| `pt_PT` | Seleccione um ficheiro PDF |
| `ru_RU` | Выберите PDF-файл |
| `zh_CN` | 选择 PDF 文件 |

### 79. `Select`

- **Used in:** JS `wp.media` frame button
- **Usage context:** Button of the media modal that confirms the selected PDF file.
- **UI space limit:** wp.media frame BUTTON (media modal primary button). Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WooCommerce | قائمة منسدلة |
| `de_DE` | WooCommerce | Auswählen |
| `es_ES` | WooCommerce | Seleccionar |
| `fr_FR` | WooCommerce | Sélectionner |
| `it_IT` | WooCommerce | Seleziona |
| `ja` | WooCommerce | 選択 |
| `pt_PT` | WooCommerce | Seleccionar |
| `ru_RU` | WooCommerce | Выбрать |
| `zh_CN` | WooCommerce | 选择 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| select | – | – | – | liste déroulante (noun) / Sélectionner (verb) | – | – | selector (noun) | – | 选择 (verb) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تحديد |
| `de_DE` | Auswählen |
| `es_ES` | Seleccionar |
| `fr_FR` | Sélectionner |
| `it_IT` | Seleziona |
| `ja` | 選択 |
| `pt_PT` | Seleccionar |
| `ru_RU` | Выбрать |
| `zh_CN` | 选择 |

### 80. `Custom text string...`

- **Used in:** JS select2 option in the CF7 field list
- **Usage context:** Special option in the CF7 field dropdown of the field mapper: lets the user type a custom text (with mail-tags) instead of picking a field. Text 'string' = text value.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

This dropdown option denotes a custom text value. Use a noun/adjectival label describing custom text, not an imperative instruction to customize something.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| custom | – | individuell (adjective) / individuelle (adjective) / individueller (adjective) / individuelles (adjective) | – | – | personalizzato (adjective) | – | – | произвольный (adjective) / пользовательский (adjective) | 自定义 (adjective) |  |
| string | – | Zeichenfolge (noun) | cadena (noun) | chaine (noun) | stringa (noun) | 文字列 (noun) | – | – | – |  |
| text | نص (noun) | – | – | – | – | – | – | – | – |  |

- `fr_FR`: Glossary 'string' = 'chaine' (1990 spelling without circumflex).
- `ru_RU`: Glossary 'custom' = 'произвольный'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | سلسلة نص مخصّصة… |
| `de_DE` | Individuelle Textzeichenfolge … |
| `es_ES` | Cadena de texto personalizada… |
| `fr_FR` | Chaine de texte personnalisée… |
| `it_IT` | Stringa di testo personalizzata… |
| `ja` | カスタムテキスト文字列… |
| `pt_PT` | Cadeia de texto personalizada… |
| `ru_RU` | Произвольная текстовая строка… |
| `zh_CN` | 自定义文本字符串… |

### 81. `All PDFs`

- **Used in:** JS select2 option + tag generator `<option>`
- **Usage context:** Dropdown option meaning 'all attached PDF files'.
- **UI space limit:** Select2 dropdown option / placeholder, dropdown 281px (or 213px) wide, h28, single line, truncates with ellipsis. Reference dropdown width varies by selector and column. Selected-value text may ellipsize; results/messages can wrap. Test actual glyph widths, padding and expanded placeholders rather than character counts.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| all | – | – | – | – | – | すべて (adjective) | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | جميع ملفات PDF |
| `de_DE` | Alle PDF-Dateien |
| `es_ES` | Todos los PDF |
| `fr_FR` | Tous les PDF |
| `it_IT` | Tutti i PDF |
| `ja` | すべての PDF |
| `pt_PT` | Todos os PDF |
| `ru_RU` | Все PDF-файлы |
| `zh_CN` | 所有 PDF |

### 82. `All`

- **Used in:** JS select2 option in the page list (image embedding)
- **Usage context:** Dropdown option meaning 'all pages' of the PDF; also shown as page caption of an embed. Plural/gender: refers to pages.
- **UI space limit:** Select2 option, 281px dropdown, single line, truncates. Reference dropdown width varies by selector and column. Selected-value text may ellipsize; results/messages can wrap. Test actual glyph widths, padding and expanded placeholders rather than character counts.

**Research / typical translations**

Refers to 'all pages' (page dropdown) – gender agreement with 'page' where applicable (es 'Todas', fr 'Toutes', it 'Tutte', pt 'Todas').

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WooCommerce | الكل |
| `de_DE` | WooCommerce | Alle |
| `es_ES` | WooCommerce | Todos |
| `fr_FR` | WooCommerce | Tout |
| `it_IT` | WooCommerce | Tutti |
| `ja` | WooCommerce | すべて |
| `pt_PT` | WooCommerce | Todos |
| `ru_RU` | WooCommerce | Все |
| `zh_CN` | WooCommerce | 全部 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| all | – | – | – | – | – | すべて (adjective) | – | – | – |  |

- `ja`: Glossary 'all' = 'すべて'; follow the Japanese handbook's hiragana spelling rather than '全て'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | الكل |
| `de_DE` | Alle |
| `es_ES` | Todas |
| `fr_FR` | Toutes |
| `it_IT` | Tutte |
| `ja` | すべて |
| `pt_PT` | Todas |
| `ru_RU` | Все |
| `zh_CN` | 全部 |

### 83. `--- EMPTY ---`

- **Used in:** JS value mapping dropdown first option
- **Usage context:** Special option meaning 'empty value' in value mappings.
- **UI space limit:** Select2 option, 281px dropdown, single line, truncates. Reference dropdown width varies by selector and column. Selected-value text may ellipsize; results/messages can wrap. Test actual glyph widths, padding and expanded placeholders rather than character counts.

**Research / typical translations**

This option denotes an empty mapping value. Preserve the distinguishing dash wrapper and translate EMPTY as a value state, not a blank page or missing service.

**WordPress glossary terms**

No glossary term occurs in this string.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | --- فارغ --- |
| `de_DE` | --- LEER --- |
| `es_ES` | --- VACÍO --- |
| `fr_FR` | --- VIDE --- |
| `it_IT` | --- VUOTO --- |
| `ja` | --- 空 --- |
| `pt_PT` | --- VAZIO --- |
| `ru_RU` | --- ПУСТО --- |
| `zh_CN` | --- 空 --- |

### 84. `The results could not be loaded.`

- **Used in:** Select2 localisation `errorLoading`
- **Usage context:** Select2 dropdown message when AJAX results failed to load.
- **UI space limit:** Select2 'no results' message inside dropdown, 281px wide, wraps. Reference dropdown width varies by selector and column. Selected-value text may ellipsize; results/messages can wrap. Test actual glyph widths, padding and expanded placeholders rather than character counts.

**Research / typical translations**

Select2/WooCommerce reference: 'Loading failed' = de 'Laden fehlgeschlagen', es 'Error al cargar', fr 'Échec du chargement', it 'Caricamento fallito', ja '読み込み失敗', pt 'Falhou ao carregar', ru 'Загрузка не удалась', zh '加载失败'.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| results | نتائج (noun) | – | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تعذّر تحميل النتائج. |
| `de_DE` | Die Ergebnisse konnten nicht geladen werden. |
| `es_ES` | No se han podido cargar los resultados. |
| `fr_FR` | Les résultats n’ont pas pu être chargés. |
| `it_IT` | Impossibile caricare i risultati. |
| `ja` | 結果を読み込めませんでした。 |
| `pt_PT` | Não foi possível carregar os resultados. |
| `ru_RU` | Не удалось загрузить результаты. |
| `zh_CN` | 无法加载结果。 |

### 85. `Please delete 1 character`

- **Used in:** Select2 `inputTooLong` (singular)
- **Usage context:** Select2 message: the search input is 1 character too long.
- **UI space limit:** Select2 message, 281px dropdown. Reference dropdown width varies by selector and column. Selected-value text may ellipsize; results/messages can wrap. Test actual glyph widths, padding and expanded placeholders rather than character counts.

**Research / typical translations**

WooCommerce (enhanced select / Select2) reference: de 'Bitte 1 Buchstaben löschen', es 'Por favor, borra 1 carácter.', fr 'Veuillez supprimer 1 caractère', it 'Elimina 1 carattere', ja '1文字削除してください', pt 'Por favor elimine 1 caractere', ru 'Пожалуйста, удалите 1 символ', zh '请删除1个字符'.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WooCommerce | الرجاء حذف حرف واحد |
| `de_DE` | WooCommerce | Bitte 1 Buchstaben löschen |
| `es_ES` | WooCommerce | Por favor, borra 1 carácter. |
| `fr_FR` | WooCommerce | Veuillez supprimer 1 caractère |
| `it_IT` | WooCommerce | Elimina 1 carattere |
| `ja` | WooCommerce | 1文字削除してください |
| `pt_PT` | WooCommerce | Por favor elimine 1 caractere |
| `ru_RU` | WooCommerce | Пожалуйста, удалите 1 символ |
| `zh_CN` | WooCommerce | 请删除1个字符 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| character | المِحْرَف (noun) | – | – | – | – | – | – | – | 字符 (noun) |  |
| delete | حذف (verb) | löschen (verb) / lösche (verb) / gelöscht (verb) | borrar (verb) | – | elimina (verb) | – | eliminar (ou excluir) (verb) | – | 删除 (verb) |  |
| please | – | – | – | Veuillez (noun) | NON SI TRADUCE (noun) | – | – | – | – |  |

- `ar`: Glossary 'character' = 'المِحْرَف' / 'characters' = 'محارف'.
- `it_IT`: Glossary 'please' = 'NON SI TRADUCE' – do not add 'per favore' or 'si prega' solely to translate the English please.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | يُرجى حذف محرف واحد |
| `de_DE` | Bitte 1 Zeichen löschen |
| `es_ES` | Por favor, borra 1 carácter |
| `fr_FR` | Veuillez supprimer 1 caractère |
| `it_IT` | Elimina 1 carattere |
| `ja` | 1文字削除してください |
| `pt_PT` | Por favor elimine 1 caractere |
| `ru_RU` | Пожалуйста, удалите 1 символ |
| `zh_CN` | 请删除 1 个字符 |

### 86. `Please delete {count} characters`

- **Used in:** Select2 `inputTooLong` (plural)
- **Usage context:** Select2 message; {count} = number of characters to delete (2 or more). The plugin has no gettext plural support here, so use a number-neutral wording if the locale has several plural forms.
- **UI space limit:** Select2 message, 281px dropdown. Reference dropdown width varies by selector and column. Selected-value text may ellipsize; results/messages can wrap. Test actual glyph widths, padding and expanded placeholders rather than character counts.

**Research / typical translations**

WooCommerce reference: 'Please delete %qty% characters'. Plural is not handled by gettext here, so number-neutral wording is used for ru/ar.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| characters | محارف (noun) | – | – | – | – | – | – | – | – |  |
| delete | حذف (verb) | löschen (verb) / lösche (verb) / gelöscht (verb) | borrar (verb) | – | elimina (verb) | – | eliminar (ou excluir) (verb) | – | 删除 (verb) |  |
| please | – | – | – | Veuillez (noun) | NON SI TRADUCE (noun) | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | يُرجى حذف {count} من المحارف |
| `de_DE` | Bitte {count} Zeichen löschen |
| `es_ES` | Por favor, borra {count} caracteres |
| `fr_FR` | Veuillez supprimer {count} caractères |
| `it_IT` | Elimina {count} caratteri |
| `ja` | {count}文字削除してください |
| `pt_PT` | Por favor elimine {count} caracteres |
| `ru_RU` | Пожалуйста, удалите лишние символы: {count} |
| `zh_CN` | 请删除 {count} 个字符 |

### 87. `Please enter 1 or more characters`

- **Used in:** Select2 `inputTooShort` (singular)
- **Usage context:** Select2 message: type at least 1 character to search.
- **UI space limit:** Select2 message, 281px dropdown. Reference dropdown width varies by selector and column. Selected-value text may ellipsize; results/messages can wrap. Test actual glyph widths, padding and expanded placeholders rather than character counts.

**Research / typical translations**

WooCommerce reference: de 'Bitte 1 oder mehr Buchstaben eingeben', es 'Por favor, introduce 1 o más caracteres', fr 'Veuillez saisir 1 caractère ou plus', it 'Inserisci 1 o più caratteri', ja '1文字以上を入力してください', pt 'Por favor, insira 1 ou mais caracteres', ru 'Пожалуйста, введите 1 или более символов', zh '请输入至少一个字符'.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WooCommerce | الرجاء ادخال حرف واحد أو أكثر |
| `de_DE` | WooCommerce | Bitte 1 oder mehr Buchstaben eingeben |
| `es_ES` | WooCommerce | Por favor, introduce 1 o más caracteres |
| `fr_FR` | WooCommerce | Veuillez saisir 1 caractère ou plus |
| `it_IT` | WooCommerce | Inserisci 1 o più caratteri |
| `ja` | WooCommerce | 1文字以上を入力してください |
| `pt_PT` | WooCommerce | Por favor, insira 1 ou mais caracteres |
| `ru_RU` | WooCommerce | Пожалуйста, введите 1 или более символов |
| `zh_CN` | WooCommerce | 请输入至少一个字符 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| characters | محارف (noun) | – | – | – | – | – | – | – | – |  |
| enter | – | – | – | Saisir (verb) / Saisissez (verb) | inserisci (verb) / Invio (noun) | – | digite (verb) | – | Enter 键 (noun) / 输入 (verb) |  |
| please | – | – | – | Veuillez (noun) | NON SI TRADUCE (noun) | – | – | – | – |  |

- `pt_PT`: Glossary 'enter' = 'digite'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | يُرجى إدخال محرف واحد أو أكثر |
| `de_DE` | Bitte 1 oder mehr Zeichen eingeben |
| `es_ES` | Por favor, introduce 1 o más caracteres |
| `fr_FR` | Veuillez saisir 1 caractère ou plus |
| `it_IT` | Inserisci 1 o più caratteri |
| `ja` | 1文字以上入力してください |
| `pt_PT` | Por favor digite 1 ou mais caracteres |
| `ru_RU` | Пожалуйста, введите хотя бы 1 символ |
| `zh_CN` | 请输入至少 1 个字符 |

### 88. `Please enter {count} or more characters`

- **Used in:** Select2 `inputTooShort` (plural)
- **Usage context:** Select2 message; {count} = minimum number of characters (2 or more). Use number-neutral wording where needed.
- **UI space limit:** Select2 message, 281px dropdown. Reference dropdown width varies by selector and column. Selected-value text may ellipsize; results/messages can wrap. Test actual glyph widths, padding and expanded placeholders rather than character counts.

**Research / typical translations**

WooCommerce reference: 'Please enter %qty% or more characters'. Number-neutral wording used for ru/ar.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| characters | محارف (noun) | – | – | – | – | – | – | – | – |  |
| enter | – | – | – | Saisir (verb) / Saisissez (verb) | inserisci (verb) / Invio (noun) | – | digite (verb) | – | Enter 键 (noun) / 输入 (verb) |  |
| please | – | – | – | Veuillez (noun) | NON SI TRADUCE (noun) | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | يُرجى إدخال {count} من المحارف أو أكثر |
| `de_DE` | Bitte {count} oder mehr Zeichen eingeben |
| `es_ES` | Por favor, introduce {count} o más caracteres |
| `fr_FR` | Veuillez saisir {count} caractères ou plus |
| `it_IT` | Inserisci {count} o più caratteri |
| `ja` | {count}文字以上入力してください |
| `pt_PT` | Por favor digite {count} ou mais caracteres |
| `ru_RU` | Пожалуйста, введите больше символов (минимум: {count}) |
| `zh_CN` | 请输入至少 {count} 个字符 |

### 89. `Loading more results…`

- **Used in:** Select2 `loadingMore`
- **Usage context:** Select2 message while more results are loaded.
- **UI space limit:** Select2 message, 281px dropdown. Reference dropdown width varies by selector and column. Selected-value text may ellipsize; results/messages can wrap. Test actual glyph widths, padding and expanded placeholders rather than character counts.

**Research / typical translations**

WooCommerce reference: de 'Weitere Ergebnisse anzeigen…', es 'Cargando más resultados…', fr 'Charger plus de résultats…', it 'Caricamento altri risultati…', ja '結果をさらに読み込み中…', pt 'A carregar mais resultados…', ru 'Загрузить ещё…', zh '载入更多结果…'.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| results | نتائج (noun) | – | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | جارٍ تحميل المزيد من النتائج… |
| `de_DE` | Weitere Ergebnisse werden geladen … |
| `es_ES` | Cargando más resultados… |
| `fr_FR` | Chargement de résultats supplémentaires… |
| `it_IT` | Caricamento di altri risultati… |
| `ja` | さらに結果を読み込み中… |
| `pt_PT` | A carregar mais resultados… |
| `ru_RU` | Загрузка других результатов… |
| `zh_CN` | 正在加载更多结果… |

### 90. `You can only select 1 item`

- **Used in:** Select2 `maximumSelected` (singular)
- **Usage context:** Select2 message: the selection is limited to 1 item. 'item' = list option (not a product).
- **UI space limit:** Select2 message, 281px dropdown. Reference dropdown width varies by selector and column. Selected-value text may ellipsize; results/messages can wrap. Test actual glyph widths, padding and expanded placeholders rather than character counts.

**Research / typical translations**

WooCommerce translates 'item' as product ('Produkt', 'artículo', 'товар') because of its shop context; here 'item' is a generic list option, so the generic term (element/项目/項目) is used.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WooCommerce | بإمكانك اختيار عنصر واحد فقط |
| `de_DE` | WooCommerce | Du kannst nur 1 Produkt auswählen |
| `es_ES` | WooCommerce | Solo puedes seleccionar 1 artículo |
| `fr_FR` | WooCommerce | Vous ne pouvez sélectionner qu’1 article |
| `it_IT` | WooCommerce | Puoi selezionare solo 1 articolo |
| `ja` | WooCommerce | 1項目のみ選択可能です |
| `pt_PT` | WooCommerce | Só pode seleccionar 1 item |
| `ru_RU` | WooCommerce | Вы можете выбрать лишь 1 вариацию |
| `zh_CN` | WooCommerce | 您只能选择1项 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| item | – | – | elemento / artículo (noun) | article  (noun) / élément (noun) | elemento (noun) | 項目 (noun) | – | – | 项目 (noun) |  |
| only | – | – | solo (adjective) / solo / solamente (adverb) | uniquement (noun) | – | – | – | – | – |  |
| select | – | – | – | liste déroulante (noun) / Sélectionner (verb) | – | – | selector (noun) | – | 选择 (verb) |  |

- `ja`: Glossary 'item' = '項目'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | يمكنك تحديد عنصر واحد فقط |
| `de_DE` | Du kannst nur 1 Element auswählen |
| `es_ES` | Solo puedes seleccionar 1 elemento |
| `fr_FR` | Vous pouvez sélectionner uniquement 1 élément |
| `it_IT` | Puoi selezionare solo 1 elemento |
| `ja` | 1項目のみ選択できます |
| `pt_PT` | Só pode seleccionar 1 item |
| `ru_RU` | Можно выбрать только 1 элемент |
| `zh_CN` | 只能选择 1 个项目 |

### 91. `You can only select {count} items`

- **Used in:** Select2 `maximumSelected` (plural)
- **Usage context:** Select2 message; {count} = maximum number of items. Use number-neutral wording where needed.
- **UI space limit:** Select2 message, 281px dropdown. Reference dropdown width varies by selector and column. Selected-value text may ellipsize; results/messages can wrap. Test actual glyph widths, padding and expanded placeholders rather than character counts.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| only | – | – | solo (adjective) / solo / solamente (adverb) | uniquement (noun) | – | – | – | – | – |  |
| select | – | – | – | liste déroulante (noun) / Sélectionner (verb) | – | – | selector (noun) | – | 选择 (verb) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | يمكنك تحديد {count} من العناصر فقط |
| `de_DE` | Du kannst nur {count} Elemente auswählen |
| `es_ES` | Solo puedes seleccionar {count} elementos |
| `fr_FR` | Vous pouvez sélectionner uniquement {count} éléments |
| `it_IT` | Puoi selezionare solo {count} elementi |
| `ja` | {count}項目のみ選択できます |
| `pt_PT` | Só pode seleccionar {count} itens |
| `ru_RU` | Максимальное количество выбранных элементов: {count} |
| `zh_CN` | 只能选择 {count} 个项目 |

### 92. `No results found`

- **Used in:** Select2 `noResults`
- **Usage context:** Select2 message: no options match the search.
- **UI space limit:** Select2 message, 281px dropdown. Reference dropdown width varies by selector and column. Selected-value text may ellipsize; results/messages can wrap. Test actual glyph widths, padding and expanded placeholders rather than character counts.

**Research / typical translations**

WooCommerce reference: de 'Keine Ergebnisse gefunden', es 'No se han encontrado resultados', fr 'Aucun résultat trouvé', it 'Nessun risultato trovato', ja '一致する結果が見つかりませんでした', pt 'Nenhum resultado encontrado', ru 'Ничего не найдено', zh '未找到结果'.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WooCommerce | لم يتم العثور على نتائج |
| `de_DE` | WooCommerce | Keine Ergebnisse gefunden |
| `es_ES` | WooCommerce | No se han encontrado resultados |
| `fr_FR` | WooCommerce | Aucun résultat trouvé |
| `it_IT` | WooCommerce | Nessun risultato trovato |
| `ja` | WooCommerce | 一致する結果が見つかりませんでした |
| `pt_PT` | WooCommerce | Nenhum resultado encontrado |
| `ru_RU` | WooCommerce | Ничего не найдено |
| `zh_CN` | WooCommerce | 未找到结果 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| no | – | – | – | Aucun (noun) | – | – | – | – | – | fr_FR glossary 'Aucun' applies to 'No …' labels |
| results | نتائج (noun) | – | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | لم يتم العثور على نتائج |
| `de_DE` | Keine Ergebnisse gefunden |
| `es_ES` | No se han encontrado resultados |
| `fr_FR` | Aucun résultat trouvé |
| `it_IT` | Nessun risultato trovato |
| `ja` | 結果が見つかりませんでした |
| `pt_PT` | Nenhum resultado encontrado |
| `ru_RU` | Ничего не найдено |
| `zh_CN` | 未找到结果 |

### 93. `Searching…`

- **Used in:** Select2 `searching`
- **Usage context:** Select2 message while searching.
- **UI space limit:** Select2 message, 281px dropdown. Reference dropdown width varies by selector and column. Selected-value text may ellipsize; results/messages can wrap. Test actual glyph widths, padding and expanded placeholders rather than character counts.

**Research / typical translations**

WooCommerce reference (enhanced select): de 'Suchen …', es 'Buscando…', fr 'Recherche…', it 'Sto cercando…', ja '検索中…', pt 'A pesquisar…', ru 'Поиск…', zh '搜索中…'.

**WordPress glossary terms**

No glossary term occurs in this string.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | جارٍ البحث… |
| `de_DE` | Suchen … |
| `es_ES` | Buscando… |
| `fr_FR` | Recherche en cours… |
| `it_IT` | Ricerca in corso… |
| `ja` | 検索中… |
| `pt_PT` | A pesquisar… |
| `ru_RU` | Поиск… |
| `zh_CN` | 正在搜索… |

### 94. `Remove all items`

- **Used in:** Select2 `removeAllItems`
- **Usage context:** Select2 tooltip of the clear button that removes all selected items.
- **UI space limit:** Tooltip/accessibility label for the Select2 clear button, not a message occupying the 281px results dropdown. The dropdown width is not a limit for this string; no tooltip-specific maximum was measured.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| all | – | – | – | – | – | すべて (adjective) | – | – | – |  |
| remove | إزالة (verb) | – | quitar / eliminar (verb) | Retirer (verb) | – | – | – | – | 移除 (verb) |  |

- `fr_FR`: Glossary 'remove' = 'Retirer'.
- `zh_CN`: Glossary 'remove' = '移除'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | إزالة جميع العناصر |
| `de_DE` | Alle Elemente entfernen |
| `es_ES` | Quitar todos los elementos |
| `fr_FR` | Retirer tous les éléments |
| `it_IT` | Rimuovi tutti gli elementi |
| `ja` | すべての項目を削除 |
| `pt_PT` | Remover todos os itens |
| `ru_RU` | Удалить все элементы |
| `zh_CN` | 移除所有项目 |

### 95. `PDF Forms`

- **Used in:** CF7 Integration page category (`add_category('pdf_forms')`)
- **Usage context:** Heading of the integration category on CF7's Integration page under which the Pdf.Ninja service is listed.
- **UI space limit:** No hard limit (error notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| forms | نماذج (noun) | – | – | – | – | – | – | – | – |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | نماذج PDF |
| `de_DE` | PDF-Formulare |
| `es_ES` | Formularios PDF |
| `fr_FR` | Formulaires PDF |
| `it_IT` | Moduli PDF |
| `ja` | PDF フォーム |
| `pt_PT` | Formulários PDF |
| `ru_RU` | PDF-формы |
| `zh_CN` | PDF 表单 |

### 96. `Copy`

- **Used in:** Copy suffix for duplicated attachments (`sanitize_file_name()`)
- **Usage context:** When a contact form is duplicated, attached PDFs are copied to the media library with the file name '<name>-<Copy>.pdf'. The translation is used in a FILE NAME and in a regex; it should be a short single word (noun 'copy', not the verb), filename-safe.
- **UI space limit:** Noun used as a filename suffix ('Copy'); keep short (~10 Latin chars).

**Research / typical translations**

Used as a file-name suffix ('form-Copy.pdf'): must be the noun.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WooCommerce | نسخ |
| `de_DE` | WooCommerce | Kopieren |
| `es_ES` | WooCommerce | Copiar |
| `fr_FR` | WooCommerce | Copier |
| `it_IT` | WooCommerce | Copia |
| `ja` | WooCommerce | コピー |
| `pt_PT` | WooCommerce | Copiar |
| `ru_RU` | WooCommerce | Копировать |
| `zh_CN` | WooCommerce | 复制 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| copy | نسخ (verb) | – | – | – | – | – | – | – | 复制 (noun) / 复制 (verb) |  |

- `zh_CN`: Glossary 'copy' (noun/verb) = '复制'; the noun '副本' would be the file-manager convention, but the glossary term is used.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | نسخة |
| `de_DE` | Kopie |
| `es_ES` | Copia |
| `fr_FR` | Copie |
| `it_IT` | Copia |
| `ja` | コピー |
| `pt_PT` | Cópia |
| `ru_RU` | Копия |
| `zh_CN` | 复制 |

### 97. `PDF Forms Filler`

- **Used in:** CF7 editor panel tab title + panel heading
- **Usage context:** Name of the plugin's panel (tab) in the CF7 contact form editor. Short localized product name: #0 without “for CF7”; keep consistent with #0 and #157.
- **UI space limit:** CF7 editor PANEL TAB, measured 132px wide (h34) for 'PDF Forms Filler'. Reference measurement only; no verified character-count maximum is established. The localized names are longer than the English tab caption; check whether the tab row wraps or the caption clips beside the localized CF7 tabs (Form, Mail, Messages, Additional Settings) in the actual layout.

**Research / typical translations**

The panel label is the short form of the localized plugin name (#0), so the panel is recognizable as belonging to the plugin listed in the Plugins screen. Research and term choices are recorded in #0.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| forms | نماذج (noun) | – | – | – | – | – | – | – | – | ar glossary 'نماذج' used |
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) | plural forms used in the name |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) | kept in Latin script |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | أداة تعبئة نماذج PDF | not measured |
| `de_DE` | PDF-Formularausfüller | not measured |
| `es_ES` | Herramienta de relleno de formularios PDF | not measured |
| `fr_FR` | Outil de remplissage de formulaires PDF | not measured |
| `it_IT` | Strumento di compilazione moduli PDF | not measured |
| `ja` | PDF フォーム入力ツール | not measured |
| `pt_PT` | Ferramenta de preenchimento de formulários PDF | not measured |
| `ru_RU` | Инструмент заполнения PDF-форм | not measured |
| `zh_CN` | PDF 表单填写工具 | not measured |

### 98. `You can use this panel to attach a PDF file to this contact form and link form-tags and mail-tags to fields in the PDF file. It is possible to link a combination of mail-tags to PDF fields. You can also embed images (from URLs or attached files) into the PDF file. Changes here are applied when the contact form is saved.`

- **Used in:** CF7 editor panel instructions
- **Usage context:** Intro paragraph of the PDF Forms Filler panel. Uses CF7 terminology: 'form-tags' and 'mail-tags' (CF7 technical terms).
- **UI space limit:** Panel H2 heading; flex, wraps. No hard limit.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| changes | – | – | – | modifications (noun) | – | – | – | – | – |  |
| contact form | – | – | – | – | – | お問い合わせフォーム (noun) | – | – | – |  |
| embed | – | – | incrustar (verb) | Contenu embarqué (noun) / Embarquer (verb) | incorpora (verb) / oggetto incorporato (noun) | – | incorporação (noun) / incorporar (ou incorporar conteúdo) (verb) | – | – |  |
| fields | حقول (noun) | – | – | – | – | – | – | – | – |  |
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| link | رابط (noun) | Link (noun) | enlazar (verb) / enlace (noun) | Lien (noun) | link (noun) / collega (verb) | – | ligação (noun) | – | 链接 (noun) |  |
| mail | – | E-Mail-Adresse (noun) / E-Mail (noun) | – | e-mail (noun) | – | – | – | – | – |  |
| panel | – | – | – | – | – | – | – | – | 面板 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |
| saved | – | – | – | – | – | – | guardado(a) (adjective) | – | – |  |

- `ar`: Glossary 'tag' = 'وسم' / 'tags' = 'وسوم'.
- `ja`: Glossary 'contact form' = 'お問い合わせフォーム'; 'Contact Form 7' (product name) is not translated.
- `ru_RU`: Glossary 'tag' = 'метка', but with the explicit note 'но в то же время: теги шаблонов, теги кода HTML' — CF7 form-tags/mail-tags are technical tags, so 'тег' is used (as in CF7's ru_RU translation).

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | يمكنك استخدام هذه اللوحة لإرفاق ملف PDF بنموذج الاتصال هذا وربط وسوم النموذج ووسوم البريد بالحقول الموجودة في ملف PDF. ويمكن ربط مجموعة من وسوم البريد بحقول PDF. يمكنك أيضًا تضمين صور (من روابط URL أو من ملفات مرفقة) في ملف PDF. تُطبَّق التغييرات هنا عند حفظ نموذج الاتصال. |
| `de_DE` | In diesem Panel kannst du eine PDF-Datei an dieses Kontaktformular anhängen und Formular-Tags und E-Mail-Tags mit Feldern in der PDF-Datei verknüpfen. Es ist möglich, eine Kombination von E-Mail-Tags mit PDF-Feldern zu verknüpfen. Du kannst auch Bilder (aus URLs oder angehängten Dateien) in die PDF-Datei einbetten. Änderungen hier werden übernommen, wenn das Kontaktformular gespeichert wird. |
| `es_ES` | Puedes usar este panel para adjuntar un archivo PDF a este formulario de contacto y enlazar etiquetas de formulario y etiquetas de correo electrónico con campos del archivo PDF. Es posible enlazar una combinación de etiquetas de correo electrónico con campos PDF. También puedes incrustar imágenes (desde URL o archivos adjuntos) en el archivo PDF. Los cambios realizados aquí se aplican al guardar el formulario de contacto. |
| `fr_FR` | Vous pouvez utiliser ce panneau pour joindre un fichier PDF à ce formulaire de contact et lier des balises de formulaire et des balises d’e-mail aux champs du fichier PDF. Il est possible de lier une combinaison de balises d’e-mail à des champs PDF. Vous pouvez également embarquer des images (à partir d’URL ou de fichiers joints) dans le fichier PDF. Les modifications effectuées ici sont appliquées lors de l’enregistrement du formulaire de contact. |
| `it_IT` | Puoi usare questo pannello per allegare un file PDF a questo modulo di contatto e collegare form-tag e mail-tag ai campi del file PDF. È possibile collegare una combinazione di mail-tag ai campi PDF. Puoi anche incorporare immagini (da URL o file allegati) nel file PDF. Le modifiche apportate qui vengono applicate quando il modulo di contatto viene salvato. |
| `ja` | このパネルでは、このお問い合わせフォームに PDF ファイルを添付し、フォームタグとメールタグを PDF ファイル内のフィールドにリンクできます。複数のメールタグを組み合わせて PDF フィールドにリンクすることも可能です。また、画像 (URL または添付ファイルから) を PDF ファイルに埋め込むこともできます。ここでの変更は、お問い合わせフォームを保存したときに適用されます。 |
| `pt_PT` | Pode utilizar este painel para anexar um ficheiro PDF a este formulário de contacto e ligar etiquetas de formulário e etiquetas de correio a campos do ficheiro PDF. É possível ligar uma combinação de etiquetas de correio a campos PDF. Também pode incorporar imagens (a partir de URL ou de ficheiros anexados) no ficheiro PDF. As alterações feitas aqui são aplicadas quando o formulário de contacto é guardado. |
| `ru_RU` | С помощью этой панели можно прикрепить PDF-файл к этой контактной форме и связать теги формы и почтовые теги с полями PDF-файла. С полями PDF можно связать и комбинацию почтовых тегов. Также можно встраивать изображения (по URL или из прикреплённых файлов) в PDF-файл. Изменения применяются при сохранении контактной формы. |
| `zh_CN` | 您可以使用此面板将 PDF 文件附加到此联系表单，并将表单标签和邮件标签链接到 PDF 文件中的字段。可以将多个邮件标签组合链接到 PDF 字段。您还可以将图片（来自 URL 或附加的文件）嵌入到 PDF 文件中。此处的更改将在保存联系表单时生效。 |

### 99. `Attach a PDF File`

- **Used in:** CF7 editor panel button
- **Usage context:** Primary button that opens the media library to attach a PDF file to the contact form.
- **UI space limit:** BUTTON label, measured 134px wide (h40) for 'Attach a PDF File'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | إرفاق ملف PDF | not measured |
| `de_DE` | PDF-Datei anhängen | not measured |
| `es_ES` | Adjuntar un archivo PDF | not measured |
| `fr_FR` | Joindre un fichier PDF | not measured |
| `it_IT` | Allega un file PDF | not measured |
| `ja` | PDF ファイルを添付 | not measured |
| `pt_PT` | Anexar um ficheiro PDF | not measured |
| `ru_RU` | Прикрепить PDF-файл | not measured |
| `zh_CN` | 附加 PDF 文件 | not measured |

### 100. `Delete`

- **Used in:** Buttons in the panel (attachments, mappings, embeds)
- **Usage context:** Button that deletes an attachment / mapping / image embed. Same as WP core 'Delete'.
- **UI space limit:** BUTTON label, measured 74px wide (h40) for 'Delete'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

Exact match in CF7/WP core: ar 'حذف', de 'Löschen', es 'Borrar', fr 'Supprimer', it 'Elimina', ja '削除', pt 'Eliminar', ru 'Удалить', zh '删除'.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | Contact Form 7 | حذف |
| `de_DE` | Contact Form 7 | Löschen |
| `es_ES` | Contact Form 7 | Borrar |
| `fr_FR` | Contact Form 7 | Supprimer |
| `it_IT` | Contact Form 7 | Elimina |
| `ja` | Contact Form 7 | 削除  |
| `pt_PT` | Contact Form 7 | Eliminar |
| `ru_RU` | Contact Form 7 | Удалить |
| `zh_CN` | Contact Form 7 | 删除 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| delete | حذف (verb) | löschen (verb) / lösche (verb) / gelöscht (verb) | borrar (verb) | – | elimina (verb) | – | eliminar (ou excluir) (verb) | – | 删除 (verb) |  |

- `es_ES`: Glossary 'delete' = 'borrar'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | حذف |
| `de_DE` | Löschen |
| `es_ES` | Borrar |
| `fr_FR` | Supprimer |
| `it_IT` | Elimina |
| `ja` | 削除 |
| `pt_PT` | Eliminar |
| `ru_RU` | Удалить |
| `zh_CN` | 删除 |

### 101. `Map Value`

- **Used in:** Field mapping row button
- **Usage context:** Small button that adds a value mapping (replace a specific CF7 value with a specific PDF field value) to a field mapping. Verb phrase.
- **UI space limit:** BUTTON label, measured 71px wide (h24, small) for 'Map Value'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

The button adds a value mapping: it assigns/replaces a specific CF7 value when filling the PDF field. Keep an action verb; do not translate map as geography or omit the operation.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| map | – | – | mapa (noun) / asignar (verb) | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | تعيين قيمة | not measured |
| `de_DE` | Wert zuordnen | not measured |
| `es_ES` | Asignar valor | not measured |
| `fr_FR` | Associer une valeur | not measured |
| `it_IT` | Mappa valore | not measured |
| `ja` | 値をマッピング | not measured |
| `pt_PT` | Mapear valor | not measured |
| `ru_RU` | Сопоставить значение | not measured |
| `zh_CN` | 映射值 | not measured |

### 102. `Options`

- **Used in:** Attachment row button
- **Usage context:** Button that toggles the options of an attached PDF file.
- **UI space limit:** BUTTON label, measured 81px wide (h40) for 'Options'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

WooCommerce/WP core 'Options'. Russian glossary: options = 'настройки'.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WooCommerce | الخيارات |
| `de_DE` | WooCommerce | Einstellungen |
| `es_ES` | WooCommerce | Opciones |
| `fr_FR` | WooCommerce | Options |
| `it_IT` | WooCommerce | Opzioni |
| `ja` | WooCommerce | オプション |
| `pt_PT` | WooCommerce | Opções |
| `ru_RU` | WooCommerce | Настройки |
| `zh_CN` | WooCommerce | 选项 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| options | خيارات (noun) | Optionen (noun) | opciones (noun) | – | – | – | – | настройки (noun) | – |  |

- `ru_RU`: Glossary 'options' = 'настройки'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | خيارات |
| `de_DE` | Optionen |
| `es_ES` | Opciones |
| `fr_FR` | Options |
| `it_IT` | Opzioni |
| `ja` | オプション |
| `pt_PT` | Opções |
| `ru_RU` | Настройки |
| `zh_CN` | 选项 |

### 103. `Skip when empty`

- **Used in:** Attachment option checkbox
- **Usage context:** Option: skip this PDF (do not attach) when none of its fields would be filled (empty). Imperative/infinitive checkbox label.
- **UI space limit:** No hard limit (help/intro paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| skip | – | – | omitir (verb) | Passer (verb) | – | – | – | – | – |  |

- `fr_FR`: Glossary 'skip' = 'Passer'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تخطي إذا كان فارغًا |
| `de_DE` | Überspringen, wenn leer |
| `es_ES` | Omitir si está vacío |
| `fr_FR` | Passer si vide |
| `it_IT` | Salta se vuoto |
| `ja` | 空の場合はスキップ |
| `pt_PT` | Ignorar se estiver vazio |
| `ru_RU` | Пропускать, если пусто |
| `zh_CN` | 为空时跳过 |

### 104. `Attach to primary email message`

- **Used in:** Attachment option checkbox
- **Usage context:** Option: attach the filled PDF to the first CF7 email message (CF7 'Mail').
- **UI space limit:** Attachment-option label in an ordinary text block; it can wrap and has no fixed height. The option text area measured roughly 521–574px across the tested locale layouts in the 744px editor panel.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| email | بريد إلكتروني (noun) | E-Mail (noun) / E-Mail-Adresse (noun) | correo electrónico (noun) | e-mail (noun) | email (noun) | メール (noun) / メールアドレス (noun) | email (noun) | – | 邮箱 (noun) |  |
| primary | – | – | principal (noun) | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | إرفاق بالبريد الرئيسي |
| `de_DE` | An primäre E-Mail anhängen |
| `es_ES` | Adjuntar al correo principal |
| `fr_FR` | Joindre à l’e-mail principal |
| `it_IT` | Allega all’email principale |
| `ja` | メインメールに添付 |
| `pt_PT` | Anexar ao email principal |
| `ru_RU` | Прикрепить к основному письму |
| `zh_CN` | 附加到主邮件 |

### 105. `Attach to secondary email message`

- **Used in:** Attachment option checkbox
- **Usage context:** Option: attach the filled PDF to the second CF7 email message (CF7 'Mail (2)', typically an autoresponder).
- **UI space limit:** Attachment-option label in an ordinary text block; it can wrap and has no fixed height. The option text area measured roughly 521–574px across the tested locale layouts in the 744px editor panel.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| email | بريد إلكتروني (noun) | E-Mail (noun) / E-Mail-Adresse (noun) | correo electrónico (noun) | e-mail (noun) | email (noun) | メール (noun) / メールアドレス (noun) | email (noun) | – | 邮箱 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | إرفاق بالبريد الثانوي |
| `de_DE` | An sekundäre E-Mail anhängen |
| `es_ES` | Adjuntar al correo secundario |
| `fr_FR` | Joindre à l’e-mail secondaire |
| `it_IT` | Allega all’email secondaria |
| `ja` | メール (2) に添付 |
| `pt_PT` | Anexar ao email secundário |
| `ru_RU` | Прикрепить к дополнительному письму |
| `zh_CN` | 附加到邮件（2） |

### 106. `Flatten`

- **Used in:** Attachment option checkbox
- **Usage context:** Option: flatten the PDF (convert form fields into static page content so they can no longer be edited). Use the PDF-industry term used by Adobe Acrobat in the locale.
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

PDF industry term (Adobe Acrobat / Acrobat Sign localized help 'Automatically flatten PDF documents'): de 'reduzieren' (Feldreduzierung), fr 'aplatir' (aplatissement), es 'acoplar' (acoplamiento), it 'appiattire' (Appiattisci campo), pt 'nivelar' (Nivelar campos de formulário), ja 'フラット化', zh_CN '拼合' (字段拼合), ru 'сведение/свести', ar 'تسطيح'.

**WordPress glossary terms**

No glossary term occurs in this string.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تسطيح |
| `de_DE` | Reduzieren |
| `es_ES` | Acoplar |
| `fr_FR` | Aplatir |
| `it_IT` | Appiattisci |
| `ja` | フラット化 |
| `pt_PT` | Nivelar |
| `ru_RU` | Свести |
| `zh_CN` | 拼合 |

### 107. `Filename (mail-tags can be used)`

- **Used in:** Attachment option text field label
- **Usage context:** Label of the output file name field (without '.pdf'); CF7 mail-tags may be used.
- **UI space limit:** Attachment-option label in an ordinary text block; it can wrap and has no fixed height. The option text area measured roughly 521–574px across the tested locale layouts in the 744px editor panel.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| filename | – | – | – | – | – | – | – | – | 文件名 (noun) |  |
| mail | – | E-Mail-Adresse (noun) / E-Mail (noun) | – | e-mail (noun) | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | اسم الملف (يمكن استخدام وسوم البريد) |
| `de_DE` | Dateiname (E-Mail-Tags möglich) |
| `es_ES` | Nombre de archivo (se pueden usar etiquetas de correo electrónico) |
| `fr_FR` | Nom de fichier (balises d’e-mail autorisées) |
| `it_IT` | Nome file (è possibile utilizzare i mail-tag) |
| `ja` | ファイル名 (メールタグ可) |
| `pt_PT` | Nome do ficheiro (podem ser utilizadas etiquetas de correio) |
| `ru_RU` | Имя файла (можно использовать почтовые теги) |
| `zh_CN` | 文件名（可用邮件标签） |

### 108. `Save PDF file on the server at the given path relative to wp-content/uploads (mail-tags can be used; if empty, PDF file is not saved on disk)`

- **Used in:** Attachment option text field label
- **Usage context:** Label of the save-path field: path relative to wp-content/uploads where the filled PDF is stored on the server. 'wp-content/uploads' must stay as is.
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| mail | – | E-Mail-Adresse (noun) / E-Mail (noun) | – | e-mail (noun) | – | – | – | – | – |  |
| on | – | – | – | – | on (noun) | – | – | – | – | it_IT glossary 'on' is the switch state; here a preposition |
| path | – | – | ruta (noun) | – | percorso (noun) | – | – | – | 路径 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |
| saved | – | – | – | – | – | – | guardado(a) (adjective) | – | – |  |
| server | خادم (noun) | Server (noun) | – | serveur (noun) | server (noun) | サーバー (noun) | – | – | 服务器 (noun) |  |
| uploads | – | Uploads (noun) | – | – | – | – | – | загрузки (noun) | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | حفظ ملف PDF على الخادم في المسار المحدد نسبةً إلى wp-content/uploads (يمكن استخدام وسوم البريد؛ إذا كان فارغًا، فلن يتم حفظ ملف PDF على القرص) |
| `de_DE` | PDF-Datei auf dem Server unter dem angegebenen Pfad relativ zu wp-content/uploads speichern (E-Mail-Tags können verwendet werden; wenn leer, wird die PDF-Datei nicht auf dem Datenträger gespeichert) |
| `es_ES` | Guardar el archivo PDF en el servidor en la ruta indicada, relativa a wp-content/uploads (se pueden usar etiquetas de correo electrónico; si está vacío, el archivo PDF no se guarda en el disco) |
| `fr_FR` | Enregistrer le fichier PDF sur le serveur dans le chemin indiqué, relatif à wp-content/uploads (les balises d’e-mail peuvent être utilisées&nbsp;; si vide, le fichier PDF n’est pas enregistré sur le disque) |
| `it_IT` | Salva il file PDF sul server nel percorso indicato relativo a wp-content/uploads (è possibile utilizzare i mail-tag; se vuoto, il file PDF non viene salvato su disco) |
| `ja` | wp-content/uploads からの相対パスで指定した場所に PDF ファイルをサーバー上で保存 (メールタグを使用可能。空の場合、PDF ファイルはディスクに保存されません) |
| `pt_PT` | Guardar o ficheiro PDF no servidor no caminho indicado, relativo a wp-content/uploads (podem ser utilizadas etiquetas de correio; se estiver vazio, o ficheiro PDF não é guardado no disco) |
| `ru_RU` | Сохранять PDF-файл на сервере по указанному пути относительно wp-content/uploads (можно использовать почтовые теги; если поле пустое, PDF-файл не сохраняется на диске) |
| `zh_CN` | 将 PDF 文件保存到服务器上相对于 wp-content/uploads 的指定路径（可使用邮件标签；如果为空，则不会将 PDF 文件保存到磁盘） |

### 109. `Add filled PDF download link to form submission response`

- **Used in:** Attachment option checkbox
- **Usage context:** Option: add a download link for the filled PDF to the response message shown to the visitor after submitting the form.
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

A filled PDF is a document whose form fields contain submission data, not merely a complete or padded document. The option adds its download link to the form-submission response.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| add | – | – | añadir (verb) | – | – | – | adicionar (verb) | – | 添加 (verb) |  |
| download | تنزيل (verb) | herunterladen (verb) / Download (noun) | – | – | – | – | descarregamento (noun) / descarregar (verb) | скачать (verb) | 下载 (verb) |  |
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) |  |
| link to | – | – | – | – | – | – | ligar a (verb) | – | – |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |
| submission | – | – | – | entrée (noun) / envoi (noun) | – | – | – | – | – |  |

- `pt_PT`: Glossary 'download' = 'descarregamento/descarregar'; glossary 'link' = 'ligação', 'link to' = 'ligar a'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | إضافة رابط تنزيل ملف PDF المملوء إلى رد إرسال النموذج |
| `de_DE` | Download-Link der ausgefüllten PDF-Datei zur Antwort auf die Formularübermittlung hinzufügen |
| `es_ES` | Añadir el enlace de descarga del PDF rellenado a la respuesta del envío del formulario |
| `fr_FR` | Ajouter un lien de téléchargement du PDF rempli à la réponse d’envoi du formulaire |
| `it_IT` | Aggiungi il link per scaricare il PDF compilato alla risposta all'invio del modulo |
| `ja` | フォーム送信時の応答に、入力済み PDF のダウンロードリンクを追加 |
| `pt_PT` | Adicionar a ligação de descarregamento do PDF preenchido à resposta de envio do formulário |
| `ru_RU` | Добавлять ссылку для скачивания заполненного PDF-файла в ответ на отправку формы |
| `zh_CN` | 在表单提交响应中添加已填写 PDF 的下载链接 |

### 110. `Trigger an automatic download of the filled PDF file on a successful form submission response`

- **Used in:** Attachment option checkbox
- **Usage context:** Option: automatically start downloading the filled PDF in the visitor's browser after a successful submission.
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| download | تنزيل (verb) | herunterladen (verb) / Download (noun) | – | – | – | – | descarregamento (noun) / descarregar (verb) | скачать (verb) | 下载 (verb) |  |
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |
| submission | – | – | – | entrée (noun) / envoi (noun) | – | – | – | – | – |  |
| successful | – | – | correcto (noun) | – | – | – | – | – | – | es_ES glossary 'correcto'; interpret according to the success-state context |
| trigger | – | – | – | – | trigger (noun) | – | accionador (noun) / desencadear (verb) | – | 触发 (verb) |  |

- `pt_PT`: Glossary 'trigger' = 'desencadear'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | بدء تنزيل ملف PDF المملوء تلقائيًا عند نجاح إرسال النموذج |
| `de_DE` | Bei erfolgreicher Formularübermittlung automatisch den Download der ausgefüllten PDF-Datei auslösen |
| `es_ES` | Iniciar automáticamente la descarga del archivo PDF rellenado cuando el envío del formulario sea correcto |
| `fr_FR` | Déclencher le téléchargement automatique du fichier PDF rempli lorsque l’envoi du formulaire réussit |
| `it_IT` | Avvia automaticamente il download del file PDF compilato quando l'invio del modulo va a buon fine |
| `ja` | フォーム送信が成功したときに、入力済み PDF ファイルの自動ダウンロードを開始 |
| `pt_PT` | Desencadear o descarregamento automático do ficheiro PDF preenchido após um envio do formulário bem-sucedido |
| `ru_RU` | Автоматически запускать скачивание заполненного PDF-файла при успешной отправке формы |
| `zh_CN` | 表单提交成功后，触发自动下载已填写的 PDF 文件 |

### 111. `Field Mapper Tool`

- **Used in:** Panel tool heading
- **Usage context:** Name of the tool that maps CF7 fields/mail-tags to PDF fields.
- **UI space limit:** Section heading in the 744px editor panel at the reference viewport; text can wrap. Check its combined width with the help link, rather than imposing the English caption width.

**Research / typical translations**

Field-mapping terminology: Spanish asignación and Portuguese mapeamento follow their glossaries; Italian mappatura, French correspondance, German Zuordnung, Russian сопоставление, Japanese マッピング, Chinese 映射 and Arabic تعيين describe linking source fields to destination PDF fields. Do not confuse mapping with image embedding.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| field | – | – | – | – | – | – | – | – | 字段 (noun) |  |
| tool | – | Werkzeug (noun) | – | – | – | – | – | – | – |  |

- `de_DE`: Glossary 'tool' = 'Werkzeug'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تعيين الحقول |
| `de_DE` | Feldzuordnung |
| `es_ES` | Asignación de campos |
| `fr_FR` | Correspondance des champs |
| `it_IT` | Mappatura dei campi |
| `ja` | フィールドマッピング |
| `pt_PT` | Mapeamento de campos |
| `ru_RU` | Сопоставление полей |
| `zh_CN` | 字段映射 |

### 112. `This tool can be used to link form fields and mail-tags to fields in the attached PDF files. When your users submit the form, input from form fields and other mail-tags will be inserted into the corresponding fields in the PDF file. CF7 to PDF field value mappings can also be created to enable the replacement of CF7 data when PDF fields are filled.`

- **Used in:** Panel tool help box
- **Usage context:** Help text of the field mapper tool. 'mail-tags' = CF7 mail-tags; 'value mappings' = CF7 value -> PDF value replacements.
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

The help text explains source CF7 fields/mail-tags, destination PDF fields, and optional value replacement. Preserve these field-mapping concepts rather than substituting image-placement instructions.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| created | – | erstellt (verb) | – | – | – | – | – | – | – |  |
| enable | تفعيل (noun) | aktivieren (verb) / aktiviere (verb) / aktiviert (verb) | activar (verb) | – | abilita (verb) | 有効化 (verb) | activar (verb) | – | 启用 (verb) | used as 'to enable the replacement' (= allow); rendered as 'to replace' / 'allow' |
| fields | حقول (noun) | – | – | – | – | – | – | – | – |  |
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) |  |
| input | – | – | – | – | input (noun) | – | – | – | – |  |
| link | رابط (noun) | Link (noun) | enlazar (verb) / enlace (noun) | Lien (noun) | link (noun) / collega (verb) | – | ligação (noun) | – | 链接 (noun) |  |
| mail | – | E-Mail-Adresse (noun) / E-Mail (noun) | – | e-mail (noun) | – | – | – | – | – |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |
| submit | – | – | – | envoyer (verb) | invia (verb) | – | enviar (verb) | – | – |  |
| tool | – | Werkzeug (noun) | – | – | – | – | – | – | – |  |
| users | مستخدمين (noun) | – | – | utilisateurs/utilisatrices (noun) / comptes (noun) / internautes (noun) | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | يمكن استخدام هذه الأداة لربط حقول النموذج ووسوم البريد بالحقول الموجودة في ملفات PDF المرفقة. عندما يرسل المستخدمون النموذج، سيتم إدراج المدخلات من حقول النموذج ووسوم البريد الأخرى في الحقول المقابلة في ملف PDF. ويمكن أيضًا إنشاء تعيينات لقيم الحقول من CF7 إلى PDF لاستبدال بيانات CF7 عند ملء حقول PDF. |
| `de_DE` | Mit diesem Werkzeug kannst du Formularfelder und E-Mail-Tags mit Feldern in den angehängten PDF-Dateien verknüpfen. Wenn deine Benutzer das Formular absenden, werden die Eingaben aus Formularfeldern und anderen E-Mail-Tags in die entsprechenden Felder der PDF-Datei eingefügt. Außerdem können Wertzuordnungen von CF7- zu PDF-Feldwerten erstellt werden, um CF7-Daten beim Ausfüllen der PDF-Felder zu ersetzen. |
| `es_ES` | Esta herramienta se puede usar para enlazar campos de formulario y etiquetas de correo electrónico con campos de los archivos PDF adjuntos. Cuando tus usuarios envíen el formulario, los datos introducidos en los campos del formulario y en otras etiquetas de correo electrónico se insertarán en los campos correspondientes del archivo PDF. También se pueden crear asignaciones de valores de campos de CF7 a PDF para reemplazar datos de CF7 al rellenar los campos del PDF. |
| `fr_FR` | Cet outil permet de lier les champs de formulaire et les balises d’e-mail aux champs des fichiers PDF joints. Lorsque vos utilisateurs envoient le formulaire, les données saisies dans les champs du formulaire et les autres balises d’e-mail sont insérées dans les champs correspondants du fichier PDF. Des correspondances de valeurs de champs CF7 vers PDF peuvent également être créées pour remplacer les données CF7 lors du remplissage des champs PDF. |
| `it_IT` | Questo strumento può essere utilizzato per collegare i campi del modulo e i mail-tag ai campi dei file PDF allegati. Quando i tuoi utenti inviano il modulo, l'input dei campi del modulo e degli altri mail-tag verrà inserito nei campi corrispondenti del file PDF. È inoltre possibile creare mappature dei valori dei campi da CF7 a PDF per sostituire i dati di CF7 durante la compilazione dei campi PDF. |
| `ja` | このツールを使用すると、フォームフィールドやメールタグを、添付された PDF ファイル内のフィールドにリンクできます。ユーザーがフォームを送信すると、フォームフィールドやその他のメールタグからの入力が PDF ファイル内の対応するフィールドに挿入されます。PDF フィールドへの入力時に CF7 のデータを置き換えるための、CF7 から PDF へのフィールド値マッピングも作成できます。 |
| `pt_PT` | Esta ferramenta pode ser utilizada para ligar campos de formulário e etiquetas de correio a campos dos ficheiros PDF anexados. Quando os seus utilizadores enviarem o formulário, os dados introduzidos nos campos do formulário e noutras etiquetas de correio serão inseridos nos campos correspondentes do ficheiro PDF. Também podem ser criados mapeamentos de valores de campos do CF7 para o PDF, para permitir a substituição de dados do CF7 ao preencher os campos PDF. |
| `ru_RU` | Этот инструмент позволяет связать поля формы и почтовые теги с полями в прикреплённых PDF-файлах. Когда пользователи отправляют форму, введённые в поля формы данные и значения других почтовых тегов вставляются в соответствующие поля PDF-файла. Также можно создавать сопоставления значений полей CF7 и PDF, чтобы заменять данные CF7 при заполнении полей PDF. |
| `zh_CN` | 此工具可用于将表单字段和邮件标签链接到附加的 PDF 文件中的字段。当用户提交表单时，表单字段和其他邮件标签中的输入内容将被插入到 PDF 文件的相应字段中。还可以创建 CF7 到 PDF 的字段值映射，以便在填写 PDF 字段时替换 CF7 数据。 |

### 113. `PDF field`

- **Used in:** Table header / tag generator row label
- **Usage context:** Column header: a field of the PDF form.
- **UI space limit:** PDF-field header cell measures about 291×58px in the field mapper; the tag-generator row header was measured at about 210px wide. The mapper and tag-generator header are separate consumers of 'PDF field'. Table columns can reflow and text can wrap; usable text width excludes padding.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| field | – | – | – | – | – | – | – | – | 字段 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | حقل PDF |
| `de_DE` | PDF-Feld |
| `es_ES` | Campo PDF |
| `fr_FR` | Champ PDF |
| `it_IT` | Campo PDF |
| `ja` | PDF フィールド |
| `pt_PT` | Campo PDF |
| `ru_RU` | Поле PDF |
| `zh_CN` | PDF 字段 |

### 114. `CF7 field/mail-tags`

- **Used in:** Table header
- **Usage context:** Column header: a CF7 form field or a combination of CF7 mail-tags (the source side of a mapping).
- **UI space limit:** Source-side header cells measure about 291×58px in the field mapper and 223×59px in the image embedding tool for English 'CF7 field/mail-tags'. The tag-generator row header uses 'PDF field', a separate source string. Columns reflow and text can wrap; usable text width excludes padding. The image-tool header is the tighter observed consumer.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| field | – | – | – | – | – | – | – | – | 字段 (noun) |  |
| mail | – | E-Mail-Adresse (noun) / E-Mail (noun) | – | e-mail (noun) | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | حقل CF7/وسوم البريد |
| `de_DE` | CF7-Feld/E-Mail-Tags |
| `es_ES` | Campo CF7/etiquetas de correo electrónico |
| `fr_FR` | Champ CF7/balises d’e-mail |
| `it_IT` | Campo CF7/mail-tag |
| `ja` | CF7 フィールド/メールタグ |
| `pt_PT` | Campo CF7/etiquetas de correio |
| `ru_RU` | Поле CF7/почтовые теги |
| `zh_CN` | CF7 字段/邮件标签 |

### 115. `Add Mapping`

- **Used in:** Field mapper button
- **Usage context:** Primary button that creates a mapping between the selected CF7 field and PDF field.
- **UI space limit:** English button measures about 112×40px; width grows with its caption and its table cell reflows. French Ajouter une correspondance measures about 201×40px and fits the 744px panel. A 16-character maximum inferred from English width is not a layout constraint.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| add | – | – | añadir (verb) | – | – | – | adicionar (verb) | – | 添加 (verb) |  |
| mapping | – | – | asignación (expression) | – | – | – | mapeamento (noun) / mapear (verb) | – | 映射 (adjective) |  |

- `es_ES`: Glossary 'add' = 'añadir'.

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | إضافة تعيين | fits, ~84×40px |
| `de_DE` | Zuordnung hinzufügen | fits, ~168×40px |
| `es_ES` | Añadir asignación | fits, ~138×40px |
| `fr_FR` | Ajouter une correspondance | fits, ~201×40px |
| `it_IT` | Aggiungi mappatura | fits, ~154×40px |
| `ja` | マッピングを追加 | fits, ~140×40px |
| `pt_PT` | Adicionar mapeamento | fits, ~171×40px |
| `ru_RU` | Добавить сопоставление | fits, ~189×40px |
| `zh_CN` | 添加映射 | fits, ~86×40px |

### 116. `Delete All`

- **Used in:** Field mapper buttons
- **Usage context:** Button that deletes all mappings / all value mappings.
- **UI space limit:** BUTTON label, measured 67px wide (h24, small) for 'Delete All'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| all | – | – | – | – | – | すべて (adjective) | – | – | – |  |
| delete | حذف (verb) | löschen (verb) / lösche (verb) / gelöscht (verb) | borrar (verb) | – | elimina (verb) | – | eliminar (ou excluir) (verb) | – | 删除 (verb) |  |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | حذف الكل | not measured |
| `de_DE` | Alle löschen | not measured |
| `es_ES` | Borrar todo | not measured |
| `fr_FR` | Tout supprimer | not measured |
| `it_IT` | Elimina tutto | not measured |
| `ja` | すべて削除 | not measured |
| `pt_PT` | Eliminar tudo | not measured |
| `ru_RU` | Удалить все | not measured |
| `zh_CN` | 全部删除 | not measured |

### 117. `Insert and Link`

- **Used in:** Buttons in panel and tag generator footer
- **Usage context:** Button that inserts the generated form-tag into the CF7 form template and links (maps) it to the selected PDF field.
- **UI space limit:** BUTTON label, measured 126px wide (h40) for 'Insert and Link'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| insert | إدراج (verb) | – | – | – | – | – | – | – | 插入 (verb) |  |
| link | رابط (noun) | Link (noun) | enlazar (verb) / enlace (noun) | Lien (noun) | link (noun) / collega (verb) | – | ligação (noun) | – | 链接 (noun) |  |

- `es_ES`: Glossary 'link' (verb) = 'enlazar'.

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | إدراج وربط | not measured |
| `de_DE` | Einfügen und verknüpfen | not measured |
| `es_ES` | Insertar y enlazar | not measured |
| `fr_FR` | Insérer et lier | not measured |
| `it_IT` | Inserisci e collega | not measured |
| `ja` | 挿入してリンク | not measured |
| `pt_PT` | Inserir e ligar | not measured |
| `ru_RU` | Вставить и связать | not measured |
| `zh_CN` | 插入并链接 | not measured |

### 118. `This button allows you to generate tags for all remaining unlinked PDF fields, insert them into the form and link them to their corresponding fields.`

- **Used in:** Panel row text next to the 'Insert & Link All' button
- **Usage context:** Explanation of the 'Insert & Link All' button.
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| allows you | – | – | – | vous permet de (verb) | – | – | – | – | – |  |
| button | زر (noun) | Button (noun) | – | – | pulsante (noun) | – | – | – | 按钮 (noun) |  |
| fields | حقول (noun) | – | – | – | – | – | – | – | – |  |
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) |  |
| insert | إدراج (verb) | – | – | – | – | – | – | – | 插入 (verb) |  |
| link | رابط (noun) | Link (noun) | enlazar (verb) / enlace (noun) | Lien (noun) | link (noun) / collega (verb) | – | ligação (noun) | – | 链接 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |
| tags | وسوم (noun) | Schlagwörter (noun) / Tags (noun) | – | – | – | – | – | – | – |  |

- `de_DE`: Glossary 'button' = 'Button'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | يتيح لك هذا الزر إنشاء وسوم لجميع حقول PDF المتبقية غير المرتبطة، وإدراجها في النموذج وربطها بالحقول المقابلة لها. |
| `de_DE` | Mit diesem Button kannst du Tags für alle verbleibenden, nicht verknüpften PDF-Felder generieren, sie in das Formular einfügen und mit den entsprechenden Feldern verknüpfen. |
| `es_ES` | Este botón te permite generar etiquetas para todos los campos PDF restantes sin enlazar, insertarlas en el formulario y enlazarlas con sus campos correspondientes. |
| `fr_FR` | Ce bouton vous permet de générer des balises pour tous les champs PDF restants non liés, de les insérer dans le formulaire et de les lier à leurs champs correspondants. |
| `it_IT` | Questo pulsante ti consente di generare i tag per tutti i campi PDF rimanenti non collegati, inserirli nel modulo e collegarli ai campi corrispondenti. |
| `ja` | このボタンを使用すると、リンクされていない残りのすべての PDF フィールドのタグを生成し、フォームに挿入して、対応するフィールドにリンクできます。 |
| `pt_PT` | Este botão permite gerar etiquetas para todos os campos PDF restantes não ligados, inseri-las no formulário e ligá-las aos campos correspondentes. |
| `ru_RU` | Эта кнопка позволяет сгенерировать теги для всех оставшихся несвязанных полей PDF, вставить их в форму и связать с соответствующими полями. |
| `zh_CN` | 此按钮可为所有剩余未链接的 PDF 字段生成标签，将其插入表单并链接到相应的字段。 |

### 119. `Insert & Link All`

- **Used in:** Button
- **Usage context:** Button that generates form-tags for all unmapped PDF fields, inserts them into the form and links them. Keep consistent with #117 and with the quoted name in #152.
- **UI space limit:** BUTTON label, measured 126px wide (h40) for 'Insert & Link All'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| all | – | – | – | – | – | すべて (adjective) | – | – | – |  |
| insert | إدراج (verb) | – | – | – | – | – | – | – | 插入 (verb) |  |
| link | رابط (noun) | Link (noun) | enlazar (verb) / enlace (noun) | Lien (noun) | link (noun) / collega (verb) | – | ligação (noun) | – | 链接 (noun) |  |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | إدراج الكل وربطه | not measured |
| `de_DE` | Alle einfügen und verknüpfen | not measured |
| `es_ES` | Insertar y enlazar todo | not measured |
| `fr_FR` | Tout insérer et lier | not measured |
| `it_IT` | Inserisci e collega tutto | not measured |
| `ja` | すべて挿入してリンク | not measured |
| `pt_PT` | Inserir e ligar tudo | not measured |
| `ru_RU` | Вставить и связать все | not measured |
| `zh_CN` | 全部插入并链接 | not measured |

### 120. `Image Embedding Tool`

- **Used in:** Panel tool heading
- **Usage context:** Name of the tool that embeds images into PDF pages.
- **UI space limit:** Section heading in the 744px editor panel at the reference viewport; text can wrap. Check its combined width with the help link, rather than imposing the English caption width.

**Research / typical translations**

This heading identifies the image-placement tool. Its controls embed images into PDF pages; distinguish it from the field mapper and use the locale’s embed terminology.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| image | – | – | – | – | immagine (noun) | – | – | – | 图片 (noun) |  |
| tool | – | Werkzeug (noun) | – | – | – | – | – | – | – |  |

- `fr_FR`: Glossary 'embed' = 'Embarquer' / 'Contenu embarqué'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تضمين الصور |
| `de_DE` | Bildeinbettung |
| `es_ES` | Incrustación de imágenes |
| `fr_FR` | Images embarquées |
| `it_IT` | Incorporamento immagini |
| `ja` | 画像埋め込み |
| `pt_PT` | Incorporação de imagens |
| `ru_RU` | Встраивание изображений |
| `zh_CN` | 图片嵌入 |

### 121. `This tool allows embedding of images into PDF files. Images are taken from file upload fields or URL field values. You can select a PDF file page and draw a bounding box for image insertion. Alternatively, you can insert your image in the center of every page.`

- **Used in:** Panel tool help box
- **Usage context:** Help text of the image embedding tool. 'bounding box' = the rectangle drawn on the page preview.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

'Bounding box' standard translations: de 'Begrenzungsrahmen' (German glossary 'box' = 'Box', therefore 'Begrenzungsbox'), fr 'cadre de délimitation', es 'cuadro delimitador', it 'riquadro di delimitazione', pt 'caixa delimitadora', ru 'ограничивающая рамка', ja 'バウンディングボックス', zh '边界框', ar 'مربع محيط'.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| box | – | Box (noun) | – | – | – | – | – | – | – |  |
| fields | حقول (noun) | – | – | – | – | – | – | – | – |  |
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| image | – | – | – | – | immagine (noun) | – | – | – | 图片 (noun) |  |
| insert | إدراج (verb) | – | – | – | – | – | – | – | 插入 (verb) |  |
| page | صفحة (noun) | Seite (noun) | página (noun) / página (noun) | page (noun) | pagina (noun) | ページ (noun) / 固定ページ (noun) | – | – | 页面 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |
| select | – | – | – | liste déroulante (noun) / Sélectionner (verb) | – | – | selector (noun) | – | 选择 (verb) |  |
| tool | – | Werkzeug (noun) | – | – | – | – | – | – | – |  |
| upload | رفع (verb) | Upload (noun) / hochladen (verb) | subida (noun) / subir (verb) | téléverser (verb) | carica (verb) / upload (noun) | – | carregamento (noun) / carregar (verb) | загрузить (verb) | 上传 (verb) |  |
| url | – | URL (noun) | URL (noun) | URL (noun) / URL (noun) | URL (noun) | – | URL (noun) | URL (noun) | URL (noun) |  |

- `ar`: Glossary 'upload' = 'رفع'.
- `de_DE`: Glossary 'box' = 'Box' → 'Begrenzungsbox' (instead of Adobe's 'Begrenzungsrahmen').
- `fr_FR`: Glossary 'upload' = 'téléverser'.
- `it_IT`: Glossary 'upload' (noun) = 'upload'.
- `zh_CN`: Glossary 'image' = '图片'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تتيح هذه الأداة تضمين الصور في ملفات PDF. تؤخذ الصور من حقول رفع الملفات أو من قيم حقول URL. يمكنك تحديد صفحة من ملف PDF ورسم مربع محيط لإدراج الصورة. وبدلًا من ذلك، يمكنك إدراج صورتك في وسط كل صفحة. |
| `de_DE` | Mit diesem Werkzeug können Bilder in PDF-Dateien eingebettet werden. Die Bilder stammen aus Datei-Upload-Feldern oder aus Werten von URL-Feldern. Du kannst eine Seite der PDF-Datei auswählen und eine Begrenzungsbox für das Einfügen des Bildes zeichnen. Alternativ kannst du dein Bild in der Mitte jeder Seite einfügen. |
| `es_ES` | Esta herramienta permite incrustar imágenes en archivos PDF. Las imágenes se toman de los campos de subida de archivos o de los valores de los campos de URL. Puedes seleccionar una página del archivo PDF y dibujar un cuadro delimitador para insertar la imagen. También puedes insertar la imagen en el centro de cada página. |
| `fr_FR` | Cet outil permet d’embarquer des images dans des fichiers PDF. Les images proviennent des champs de téléversement de fichiers ou des valeurs de champs d’URL. Vous pouvez sélectionner une page du fichier PDF et dessiner un cadre de délimitation pour l’insertion de l’image. Vous pouvez également insérer votre image au centre de chaque page. |
| `it_IT` | Questo strumento consente di incorporare immagini nei file PDF. Le immagini vengono prese dai campi di upload dei file o dai valori dei campi URL. Puoi selezionare una pagina del file PDF e disegnare un riquadro di delimitazione per l'inserimento dell'immagine. In alternativa, puoi inserire l'immagine al centro di ogni pagina. |
| `ja` | このツールを使用すると、PDF ファイルに画像を埋め込むことができます。画像は、ファイルアップロードフィールドまたは URL フィールドの値から取得されます。PDF ファイルのページを選択し、画像を挿入するためのバウンディングボックスを描画できます。または、すべてのページの中央に画像を挿入することもできます。 |
| `pt_PT` | Esta ferramenta permite incorporar imagens em ficheiros PDF. As imagens são obtidas a partir de campos de carregamento de ficheiros ou de valores de campos de URL. Pode seleccionar uma página do ficheiro PDF e desenhar uma caixa delimitadora para a inserção da imagem. Em alternativa, pode inserir a imagem no centro de cada página. |
| `ru_RU` | Этот инструмент позволяет встраивать изображения в PDF-файлы. Изображения берутся из полей загрузки файлов или из значений полей URL. Вы можете выбрать страницу PDF-файла и нарисовать ограничивающую рамку для вставки изображения. Также можно вставить изображение в центр каждой страницы. |
| `zh_CN` | 此工具可将图片嵌入到 PDF 文件中。图片取自文件上传字段或 URL 字段的值。您可以选择 PDF 文件的页面并绘制一个边界框来插入图片。或者，您也可以将图片插入到每一页的中央。 |

### 122. `Embed Image`

- **Used in:** Image embedding button
- **Usage context:** Primary button that adds an image embed.
- **UI space limit:** English button measures about 114×40px; width grows with its caption and its table cell reflows. French Embarquer une image measures about 164×40px and fits the 744px panel in a roughly 172px cell. Russian caption measured about 177×40px and also fit.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| embed | – | – | incrustar (verb) | Contenu embarqué (noun) / Embarquer (verb) | incorpora (verb) / oggetto incorporato (noun) | – | incorporação (noun) / incorporar (ou incorporar conteúdo) (verb) | – | – |  |
| image | – | – | – | – | immagine (noun) | – | – | – | 图片 (noun) |  |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | تضمين صورة | fits, ~93×40px |
| `de_DE` | Bild einbetten | fits, ~118×40px |
| `es_ES` | Incrustar imagen | fits, ~133×40px |
| `fr_FR` | Embarquer une image | fits, ~164×40px |
| `it_IT` | Incorpora immagine | fits, ~152×40px |
| `ja` | 画像を埋め込む | fits, ~126×40px |
| `pt_PT` | Incorporar imagem | fits, ~146×40px |
| `ru_RU` | Встроить изображение | fits, ~177×40px |
| `zh_CN` | 嵌入图片 | fits, ~86×40px |

### 123. `PDF file`

- **Used in:** Table header
- **Usage context:** Column header: target PDF file of the image embed.
- **UI space limit:** TABLE HEADER, measured 223px wide (h59) for 'PDF file'. Reference cell size only; columns reflow and text can wrap. Usable text width excludes padding.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | ملف PDF |
| `de_DE` | PDF-Datei |
| `es_ES` | Archivo PDF |
| `fr_FR` | Fichier PDF |
| `it_IT` | File PDF |
| `ja` | PDF ファイル |
| `pt_PT` | Ficheiro PDF |
| `ru_RU` | PDF-файл |
| `zh_CN` | PDF 文件 |

### 124. `Page`

- **Used in:** Table header
- **Usage context:** Column header: target PDF page number.
- **UI space limit:** TABLE HEADER, measured 74px wide (h59) for 'Page'. Reference cell size only; columns reflow and text can wrap. Usable text width excludes padding.

**Research / typical translations**

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WooCommerce | صفحة |
| `de_DE` | WooCommerce | Seite |
| `es_ES` | WooCommerce | Página |
| `fr_FR` | WooCommerce | Page |
| `it_IT` | WooCommerce | Pagina |
| `ja` | WooCommerce | ページ |
| `pt_PT` | WooCommerce | Página |
| `ru_RU` | WooCommerce | Страница |
| `zh_CN` | WooCommerce | 页面 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| page | صفحة (noun) | Seite (noun) | página (noun) / página (noun) | page (noun) | pagina (noun) | ページ (noun) / 固定ページ (noun) | – | – | 页面 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | صفحة |
| `de_DE` | Seite |
| `es_ES` | Página |
| `fr_FR` | Page |
| `it_IT` | Pagina |
| `ja` | ページ |
| `pt_PT` | Página |
| `ru_RU` | Страница |
| `zh_CN` | 页面 |

### 125. `Select a region where the image needs to be embeded.`

- **Used in:** Image embedding page preview hint
- **Usage context:** Instruction to draw a rectangle on the page preview where the image will be placed. ('embeded' is a source typo.)
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| image | – | – | – | – | immagine (noun) | – | – | – | 图片 (noun) |  |
| select | – | – | – | liste déroulante (noun) / Sélectionner (verb) | – | – | selector (noun) | – | 选择 (verb) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | حدد المنطقة التي يجب تضمين الصورة فيها. |
| `de_DE` | Wähle einen Bereich aus, in den das Bild eingebettet werden soll. |
| `es_ES` | Selecciona una región en la que se deba incrustar la imagen. |
| `fr_FR` | Sélectionnez la zone dans laquelle l’image doit être embarquée. |
| `it_IT` | Seleziona l'area in cui deve essere incorporata l'immagine. |
| `ja` | 画像を埋め込む領域を選択してください。 |
| `pt_PT` | Seleccione a região onde a imagem deve ser incorporada. |
| `ru_RU` | Выделите область, в которую нужно встроить изображение. |
| `zh_CN` | 选择需要嵌入图片的区域。 |

### 126. `Top`

- **Used in:** Coordinate input label
- **Usage context:** Label of the 'top' coordinate (distance from the top edge, in points). Position label, not 'upper'.
- **UI space limit:** Inline coordinate label, measured 24px wide (h16) for 'Top'. Text width is content-dependent, not a fixed slot; the coordinate row can reflow. Keep units concise and test the complete row.

**Research / typical translations**

WP core 'Top' (position): de 'Oben', es 'Arriba', fr 'Haut', it 'Alto' (glossary: 'in alto'), ja '上', pt 'Superior', ru 'Сверху', zh '顶部', ar 'أعلى'.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WordPress core | أعلى |
| `de_DE` | WordPress core | Oben |
| `es_ES` | WordPress core | Arriba |
| `fr_FR` | WordPress core | Haut |
| `it_IT` | WordPress core | Alto |
| `ja` | WordPress core | 上 |
| `pt_PT` | WordPress core | Superior |
| `ru_RU` | WordPress core | Сверху |
| `zh_CN` | WordPress core | 顶部 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| top | – | – | – | – | in alto (adverb) | – | – | – | – |  |

- `it_IT`: Glossary 'top' = 'in alto'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | أعلى |
| `de_DE` | Oben |
| `es_ES` | Arriba |
| `fr_FR` | Haut |
| `it_IT` | In alto |
| `ja` | 上 |
| `pt_PT` | Superior |
| `ru_RU` | Сверху |
| `zh_CN` | 顶部 |

### 127. `Left`

- **Used in:** Coordinate input label
- **Usage context:** Label of the 'left' coordinate (distance from the left edge, in points).
- **UI space limit:** Inline coordinate label, measured 26px wide (h16) for 'Left'. Text width is content-dependent, not a fixed slot; the coordinate row can reflow. Keep units concise and test the complete row.

**Research / typical translations**

WooCommerce 'Left': de 'Links', es 'Izquierda', fr 'Gauche', it 'Sinistra', ja '左', pt 'Esquerda', ru 'Слева', zh '左', ar 'يسار'.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WooCommerce | يمين الرقم |
| `de_DE` | WooCommerce | Links |
| `es_ES` | WooCommerce | Izquierda |
| `fr_FR` | WooCommerce | Gauche |
| `it_IT` | WooCommerce | Sinistra |
| `ja` | WooCommerce | 左 |
| `pt_PT` | WooCommerce | Esquerda |
| `ru_RU` | WooCommerce | Слева |
| `zh_CN` | WooCommerce | 左 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| left | يسار (noun) / يمين (noun) / يسار (noun) | – | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | يسار | not measured |
| `de_DE` | Links | not measured |
| `es_ES` | Izquierda | not measured |
| `fr_FR` | Gauche | not measured |
| `it_IT` | A sinistra | not measured |
| `ja` | 左 | not measured |
| `pt_PT` | Esquerda | not measured |
| `ru_RU` | Слева | not measured |
| `zh_CN` | 左侧 | not measured |

### 128. `Width`

- **Used in:** Coordinate input label
- **Usage context:** Label of the image box width (points).
- **UI space limit:** Inline coordinate label, measured 38px wide (h16) for 'Width'. Text width is content-dependent, not a fixed slot; the coordinate row can reflow. Keep units concise and test the complete row.

**Research / typical translations**

WooCommerce/glossary 'Width': de 'Breite', es 'Ancho', fr 'Largeur', it 'Larghezza', ja '幅', pt 'Largura', ru 'Ширина', zh '宽度', ar 'العرض'.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WooCommerce | العرض |
| `de_DE` | WooCommerce | Breite |
| `es_ES` | WooCommerce | Ancho |
| `fr_FR` | WooCommerce | Largeur |
| `it_IT` | WooCommerce | Larghezza |
| `ja` | WooCommerce | 幅 |
| `pt_PT` | WooCommerce | Largura |
| `ru_RU` | WooCommerce | Ширина |
| `zh_CN` | WooCommerce | 宽度 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| width | عرض (noun) | – | – | – | larghezza (noun) | – | – | – | 宽度 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | العرض |
| `de_DE` | Breite |
| `es_ES` | Ancho |
| `fr_FR` | Largeur |
| `it_IT` | Larghezza |
| `ja` | 幅 |
| `pt_PT` | Largura |
| `ru_RU` | Ширина |
| `zh_CN` | 宽度 |

### 129. `Height`

- **Used in:** Coordinate input label
- **Usage context:** Label of the image box height (points).
- **UI space limit:** Inline coordinate label, measured 43px wide (h16) for 'Height'. Text width is content-dependent, not a fixed slot; the coordinate row can reflow. Keep units concise and test the complete row.

**Research / typical translations**

WooCommerce/glossary 'Height': de 'Höhe', es 'Alto', fr 'Hauteur', it 'Altezza', ja '高さ', pt 'Altura', ru 'Высота', zh '高度', ar 'الارتفاع'.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WooCommerce | الارتفاع |
| `de_DE` | WooCommerce | Höhe |
| `es_ES` | WooCommerce | Alto |
| `fr_FR` | WooCommerce | Hauteur |
| `it_IT` | WooCommerce | Altezza |
| `ja` | WooCommerce | 高さ |
| `pt_PT` | WooCommerce | Altura |
| `ru_RU` | WooCommerce | Высота |
| `zh_CN` | WooCommerce | 高度 |

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| height | ارتفاع (noun) | – | – | – | altezza (noun) | – | – | – | 高度 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | الارتفاع |
| `de_DE` | Höhe |
| `es_ES` | Alto |
| `fr_FR` | Hauteur |
| `it_IT` | Altezza |
| `ja` | 高さ |
| `pt_PT` | Altura |
| `ru_RU` | Высота |
| `zh_CN` | 高度 |

### 130. `pts`

- **Used in:** Unit after coordinate inputs
- **Usage context:** Abbreviation of typographic points (PostScript points, 1/72 inch). Use the locale's usual abbreviation.
- **UI space limit:** Inline unit suffix 'pts' after each coordinate input, h16. Text width is content-dependent, not a fixed slot; the coordinate row can reflow. Keep units concise and test the complete row.

**Research / typical translations**

Typographic point: 'pt' in Latin-script locales and Japanese (Adobe/Microsoft Office ja use 'pt'), 'пт' in Russian, '磅' in Simplified Chinese (Microsoft Office/Adobe zh_CN), 'نقطة' in Arabic.

**WordPress glossary terms**

No glossary term occurs in this string.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | نقطة |
| `de_DE` | pt |
| `es_ES` | pt |
| `fr_FR` | pt |
| `it_IT` | pt |
| `ja` | pt |
| `pt_PT` | pt |
| `ru_RU` | пт |
| `zh_CN` | 磅 |

### 131. `Have a question/comment/problem?  Feel free to use {a-href-forum}the support forum{/a} and view {a-href-tutorial}the tutorial video{/a}.`

- **Used in:** Panel footer / tag generator help
- **Usage context:** Footer text. {a-href-forum}...{/a} links to the WordPress.org support forum, {a-href-tutorial}...{/a} links to the YouTube tutorial video.
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| comment | تعليق (noun) | kommentieren (verb) / kommentiere (verb) / kommentiert (verb) / Kommentar (noun) | comentario (noun) / comentar (verb) | commenter (verb) / commentaire (noun) | commento (noun) / commenta (verb) | コメントする (verb) / コメント (noun) | – | – | 评论 (noun) |  |
| feel free | – | – | no dudes (expression) | – | – | – | – | – | – |  |
| forum | منتدى (noun) | – | – | – | forum (noun) | – | – | – | 论坛 (noun) |  |
| problem | مشكلة (noun) | – | – | – | – | – | – | – | – |  |
| support | – | – | es compatible con / dar soporte (verb) | support (noun) / prendre en charge (verb) | – | – | – | – | – |  |
| tutorial | – | – | – | – | – | – | – | – | 教程 (noun) |  |
| video | فيديو (noun) | Video (noun) | – | vidéo (noun) | video (noun) | 動画 (noun) | – | – | 视频 (noun) |  |
| view | – | anzeigen (verb) / Aufruf (noun) | – | vue (noun) / voir (verb) | visualizza (verb) | 表示 (verb) / ビュー (noun) | visualização (noun) / ver (verb) | – | – |  |

- `de_DE`: Glossary 'view' = 'anzeigen' refers to the UI action 'View'; for watching a video the natural 'sieh dir … an' is kept.
- `es_ES`: Glossary 'feel free' = 'no dudes' → use informal 'tú' consistently.
- `ja`: Glossary 'video' = '動画'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | هل لديك سؤال أو تعليق أو مشكلة؟ لا تتردد في استخدام {a-href-forum}منتدى الدعم{/a} ومشاهدة {a-href-tutorial}الفيديو التعليمي{/a}. |
| `de_DE` | Hast du eine Frage, einen Kommentar oder ein Problem? Nutze gerne {a-href-forum}das Support-Forum{/a} und sieh dir {a-href-tutorial}das Tutorial-Video{/a} an. |
| `es_ES` | ¿Tienes alguna pregunta, comentario o problema? No dudes en usar {a-href-forum}el foro de soporte{/a} y ver {a-href-tutorial}el vídeo tutorial{/a}. |
| `fr_FR` | Une question, un commentaire, un problème&nbsp;? N’hésitez pas à utiliser {a-href-forum}le forum de support{/a} et à voir {a-href-tutorial}la vidéo du tutoriel{/a}. |
| `it_IT` | Hai una domanda, un commento o un problema? Usa pure {a-href-forum}il forum di supporto{/a} e visualizza {a-href-tutorial}il video tutorial{/a}. |
| `ja` | 質問、コメント、問題がありますか ? お気軽に{a-href-forum}サポートフォーラム{/a}をご利用いただき、{a-href-tutorial}チュートリアル動画{/a}をご覧ください。 |
| `pt_PT` | Tem uma pergunta, comentário ou problema? Esteja à vontade para utilizar {a-href-forum}o fórum de suporte{/a} e ver {a-href-tutorial}o vídeo tutorial{/a}. |
| `ru_RU` | Есть вопрос, комментарий или проблема? Обращайтесь на {a-href-forum}форум поддержки{/a} и посмотрите {a-href-tutorial}обучающее видео{/a}. |
| `zh_CN` | 有疑问、评论或问题？欢迎使用{a-href-forum}支持论坛{/a}并观看{a-href-tutorial}教程视频{/a}。 |

### 132. `Failed to download file: {error-message}`

- **Used in:** `download_file()` exception
- **Usage context:** Error when downloading an image (for image embedding) from a URL fails. {error-message} = WP HTTP API error.
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| download | تنزيل (verb) | herunterladen (verb) / Download (noun) | – | – | – | – | descarregamento (noun) / descarregar (verb) | скачать (verb) | 下载 (verb) |  |
| error | خطأ (noun) | Fehler (noun) | – | erreur (noun) | errore (noun) | エラー (noun) | – | – | – | only part of a product name / URL / placeholder – not translated |
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | فشل تنزيل الملف: {error-message} |
| `de_DE` | Die Datei konnte nicht heruntergeladen werden: {error-message} |
| `es_ES` | No se ha podido descargar el archivo: {error-message} |
| `fr_FR` | Échec du téléchargement du fichier&nbsp;: {error-message} |
| `it_IT` | Impossibile scaricare il file: {error-message} |
| `ja` | ファイルのダウンロードに失敗しました: {error-message} |
| `pt_PT` | Falha ao descarregar o ficheiro: {error-message} |
| `ru_RU` | Не удалось скачать файл: {error-message} |
| `zh_CN` | 下载文件失败：{error-message} |

### 133. `File type {mime-type} of {file} is unsupported for {purpose}`

- **Used in:** `get_image_embed_*` / `fill_pdf` exceptions (2 places)
- **Usage context:** Error: the MIME type of a file is not supported for the given purpose. {mime-type}, {file} (file name), {purpose} = #134 or #139. The purpose phrase is inserted after 'for'.
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

The {purpose} phrase (#134, #139) is inserted after the preposition; translations of #133, #134 and #139 are designed together (case/article agreement: ru genitive 'для встраивания', es 'con la incrustación', de 'für die Bildeinbettung').

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| mime | – | – | – | – | – | – | – | – | MIME (noun) | only part of a product name / URL / placeholder – not translated |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | نوع الملف {mime-type} للملف {file} غير مدعوم لغرض {purpose} |
| `de_DE` | Der Dateityp {mime-type} von {file} wird für {purpose} nicht unterstützt |
| `es_ES` | El tipo de archivo {mime-type} de {file} no es compatible con {purpose} |
| `fr_FR` | Le type de fichier {mime-type} de {file} n’est pas pris en charge pour {purpose} |
| `it_IT` | Il tipo di file {mime-type} di {file} non è supportato per {purpose} |
| `ja` | {file} のファイルタイプ {mime-type} は{purpose}ではサポートされていません |
| `pt_PT` | O tipo de ficheiro {mime-type} de {file} não é suportado para {purpose} |
| `ru_RU` | Тип {mime-type} файла {file} не поддерживается для {purpose} |
| `zh_CN` | {file} 的文件类型 {mime-type} 不支持{purpose} |

### 134. `image embedding`

- **Used in:** Substituted into {purpose} of #133
- **Usage context:** Purpose phrase (lowercase noun phrase) = 'image embedding'. Must fit grammatically into #133.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| image | – | – | – | – | immagine (noun) | – | – | – | 图片 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تضمين الصور |
| `de_DE` | die Bildeinbettung |
| `es_ES` | la incrustación de imágenes |
| `fr_FR` | les images embarquées |
| `it_IT` | l'incorporamento di immagini |
| `ja` | 画像の埋め込み |
| `pt_PT` | a incorporação de imagens |
| `ru_RU` | встраивания изображений |
| `zh_CN` | 图片嵌入 |

### 135. `, `

- **Used in:** `fill_pdfs()` value concatenation
- **Usage context:** Separator used when several CF7 values (e.g. checkboxes) are put into a single-line PDF text field. Comma + space in most locales; locale-specific comma for ar/ja/zh.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

WP core translates ', ' as: ar '، ', ja '、', zh_CN '、', others ', '.

Identical string in official translations on translate.wordpress.org:

| Locale | Source | Translation |
|---|---|---|
| `ar` | WordPress core | ،  |
| `de_DE` | WordPress core | ,  |
| `es_ES` | WordPress core | ,  |
| `fr_FR` | WordPress core | ,  |
| `it_IT` | WordPress core | ,  |
| `ja` | WordPress core | 、 |
| `pt_PT` | WordPress core | ,  |
| `ru_RU` | WordPress core | ,  |
| `zh_CN` | WordPress core | 、 |

**WordPress glossary terms**

No glossary term occurs in this string.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | ،  |
| `de_DE` | ,  |
| `es_ES` | ,  |
| `fr_FR` | ,  |
| `it_IT` | ,  |
| `ja` | 、 |
| `pt_PT` | ,  |
| `ru_RU` | ,  |
| `zh_CN` | 、 |

### 136. `Attachment file is not accessible`

- **Used in:** Exceptions (2 places)
- **Usage context:** Error: the attached PDF file cannot be accessed.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| attachment | مُرفق (noun) | Anhang (noun) | adjunto (noun) | fichier joint (noun) | allegato (noun) | 添付ファイル (noun) | – | вложение (noun) | – |  |
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |

- `ru_RU`: Glossary 'attachment' = 'вложение'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | لا يمكن الوصول إلى ملف المُرفق |
| `de_DE` | Auf die Datei des Anhangs kann nicht zugegriffen werden |
| `es_ES` | No se puede acceder al archivo adjunto |
| `fr_FR` | Le fichier joint n’est pas accessible |
| `it_IT` | Il file allegato non è accessibile |
| `ja` | 添付ファイルにアクセスできません |
| `pt_PT` | O ficheiro anexado não está acessível |
| `ru_RU` | Файл вложения недоступен |
| `zh_CN` | 无法访问附件文件 |

### 137. `Error generating PDF: {error-message} at {error-file}:{error-line}`

- **Used in:** Exception wrapper in `fill_pdfs()`
- **Usage context:** Error shown to admins/logs. {error-message}, {error-file} (PHP file path), {error-line} (line number); 'at {error-file}:{error-line}' must keep the 'file:line' form.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| error | خطأ (noun) | Fehler (noun) | – | erreur (noun) | errore (noun) | エラー (noun) | – | – | – |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | خطأ في إنشاء ملف PDF: {error-message} في {error-file}:{error-line} |
| `de_DE` | Fehler beim Erzeugen der PDF-Datei: {error-message} in {error-file}:{error-line} |
| `es_ES` | Error al generar el PDF: {error-message} en {error-file}:{error-line} |
| `fr_FR` | Erreur lors de la génération du PDF&nbsp;: {error-message} dans {error-file}:{error-line} |
| `it_IT` | Errore durante la generazione del PDF: {error-message} in {error-file}:{error-line} |
| `ja` | PDF の生成中にエラーが発生しました: {error-message} ({error-file}:{error-line}) |
| `pt_PT` | Erro ao gerar o PDF: {error-message} em {error-file}:{error-line} |
| `ru_RU` | Ошибка создания PDF: {error-message} в {error-file}:{error-line} |
| `zh_CN` | 生成 PDF 时出错：{error-message}，位于 {error-file}:{error-line} |

### 138. `An error occurred while processing a PDF: {error-message}`

- **Used in:** CF7 submission error (`wpcf7_before_send_mail`)
- **Usage context:** Error message when processing (filling) a PDF fails during form submission. {error-message} = details.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| an error occurred | – | – | – | Une erreur s’est produite (noun) | – | – | – | – | – |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | حدث خطأ أثناء معالجة ملف PDF: {error-message} |
| `de_DE` | Beim Verarbeiten einer PDF-Datei ist ein Fehler aufgetreten: {error-message} |
| `es_ES` | Se ha producido un error al procesar un PDF: {error-message} |
| `fr_FR` | Une erreur s’est produite lors du traitement d’un PDF&nbsp;: {error-message} |
| `it_IT` | Si è verificato un errore durante l'elaborazione di un PDF: {error-message} |
| `ja` | PDF の処理中にエラーが発生しました: {error-message} |
| `pt_PT` | Ocorreu um erro ao processar um PDF: {error-message} |
| `ru_RU` | При обработке PDF произошла ошибка: {error-message} |
| `zh_CN` | 处理 PDF 时发生错误：{error-message} |

### 139. `PDF form filling`

- **Used in:** Substituted into {purpose} of #133
- **Usage context:** Purpose phrase (lowercase) = 'PDF form filling'. Must fit grammatically into #133. Keep lowercase in translations because it is inserted mid-sentence after 'unsupported for'.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

This purpose phrase describes filling existing PDF form fields. Its grammar must agree with the surrounding “File type {mime-type} of {file} is unsupported for {purpose}” message, rather than read as a standalone heading or command.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | ملء نماذج PDF |
| `de_DE` | das Ausfüllen von PDF-Formularen |
| `es_ES` | el rellenado de formularios PDF |
| `fr_FR` | le remplissage de formulaires PDF |
| `it_IT` | la compilazione dei moduli PDF |
| `ja` | PDF フォームへの入力 |
| `pt_PT` | o preenchimento de formulários PDF |
| `ru_RU` | заполнения PDF-форм |
| `zh_CN` | PDF 表单填写 |

### 140. `Could not read attached file`

- **Used in:** Exception
- **Usage context:** Error: the attached file could not be read.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تعذّرت قراءة الملف المرفق |
| `de_DE` | Die angehängte Datei konnte nicht gelesen werden |
| `es_ES` | No se ha podido leer el archivo adjunto |
| `fr_FR` | Impossible de lire le fichier joint |
| `it_IT` | Impossibile leggere il file allegato |
| `ja` | 添付ファイルを読み取れませんでした |
| `pt_PT` | Não foi possível ler o ficheiro anexado |
| `ru_RU` | Не удалось прочитать прикреплённый файл |
| `zh_CN` | 无法读取附加的文件 |

### 141. `No service`

- **Used in:** `get_service()` exception
- **Usage context:** Error: no PDF processing service (Pdf.Ninja API or Enterprise Extension) is available/configured.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

The error identifies an unavailable PDF processing service provider. Translate the service concept, not radio reception or a missing signal.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| no | – | – | – | Aucun (noun) | – | – | – | – | – | fr_FR glossary 'Aucun' applies to 'No …' labels |
| service | – | – | – | – | – | – | – | – | 服务 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | لا توجد خدمة |
| `de_DE` | Kein Dienst verfügbar |
| `es_ES` | No hay ningún servicio |
| `fr_FR` | Aucun service |
| `it_IT` | Nessun servizio |
| `ja` | サービスがありません |
| `pt_PT` | Nenhum serviço |
| `ru_RU` | Нет доступного сервиса |
| `zh_CN` | 无可用服务 |

### 142. `This PDF file does not appear to contain a PDF form.  See https://acrobat.adobe.com/us/en/acrobat/how-to/create-fillable-pdf-forms-creator.html for more information.`

- **Used in:** AJAX `query_pdf_fields` response
- **Usage context:** Shown in the tag generator when the attached PDF has no fillable form. The URL must be kept as is.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

Preserve the Adobe URL exactly, including its locale path and ASCII URL punctuation. Translate surrounding instructions, not the URL itself.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| adobe | – | – | – | – | – | – | – | – | Adobe (noun) | only part of a product name / URL / placeholder – not translated |
| create | – | erstellen (verb) / erstelle (verb) / erstellt (verb) | – | – | – | – | – | – | 创建 (verb) | only part of a product name / URL / placeholder – not translated |
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) |  |
| how-to | – | – | guía práctica (expression) | – | – | – | – | – | – | only part of a product name / URL / placeholder – not translated |
| html | HTML (abbreviation) | – | – | HTML (noun) | – | – | – | – | – | only part of a product name / URL / placeholder – not translated |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | لا يبدو أن ملف PDF هذا يحتوي على نموذج PDF. راجع https://acrobat.adobe.com/us/en/acrobat/how-to/create-fillable-pdf-forms-creator.html لمزيد من المعلومات. |
| `de_DE` | Diese PDF-Datei scheint kein PDF-Formular zu enthalten. Weitere Informationen findest du unter https://acrobat.adobe.com/us/en/acrobat/how-to/create-fillable-pdf-forms-creator.html. |
| `es_ES` | Este archivo PDF no parece contener un formulario PDF. Consulta https://acrobat.adobe.com/us/en/acrobat/how-to/create-fillable-pdf-forms-creator.html para obtener más información. |
| `fr_FR` | Ce fichier PDF ne semble pas contenir de formulaire PDF. Consultez https://acrobat.adobe.com/us/en/acrobat/how-to/create-fillable-pdf-forms-creator.html pour plus d’informations. |
| `it_IT` | Questo file PDF non sembra contenere un modulo PDF. Per maggiori informazioni consulta https://acrobat.adobe.com/us/en/acrobat/how-to/create-fillable-pdf-forms-creator.html. |
| `ja` | この PDF ファイルには PDF フォームが含まれていないようです。詳しくは https://acrobat.adobe.com/us/en/acrobat/how-to/create-fillable-pdf-forms-creator.html を参照してください。 |
| `pt_PT` | Este ficheiro PDF não parece conter um formulário PDF. Consulte https://acrobat.adobe.com/us/en/acrobat/how-to/create-fillable-pdf-forms-creator.html para mais informações. |
| `ru_RU` | Этот PDF-файл, по-видимому, не содержит PDF-формы. Подробнее см. https://acrobat.adobe.com/us/en/acrobat/how-to/create-fillable-pdf-forms-creator.html. |
| `zh_CN` | 此 PDF 文件似乎不包含 PDF 表单。有关详细信息，请参阅 https://acrobat.adobe.com/us/en/acrobat/how-to/create-fillable-pdf-forms-creator.html。 |

### 143. `Invalid post ID`

- **Used in:** AJAX `query_cf7_fields` exception
- **Usage context:** Error: the post ID of the contact form (CF7 forms are WordPress posts of type wpcf7_contact_form) is invalid. WP core 'Invalid post ID.'
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

WP core 'Invalid post ID.': ar 'رقم المقالة خاطئ.' / glossary 'مقالة', de 'Die Beitrags-ID ist ungültig.', es 'ID de entrada no válido.', fr 'Identifiant de l’article non valide.' (fr glossary: post = 'publication'), it 'ID articolo non valido.', ja '無効な投稿 ID です。', pt 'ID de artigo inválido.', ru 'Неверный ID записи.', zh '文章 ID 无效。'.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| invalid | غير صالح (adjective) / غير صالحة (adjective) | ungültig (adjective) | no válido (noun) | invalide (adjective) | non valido (adjective) | 無効 (adjective) / 不正な (adjective) / 間違った (adjective) | – | – | 无效 (adjective) |  |
| post | مقالة (noun) | Beitrag (noun) / Beitrag veröffentlichen (verb) / Beitrag veröffentlicht (verb) | entrada (noun) | publication (noun) / publier (verb) | pubblica (verb) / articolo (noun) | 投稿 (noun) / 投稿する (verb) | publicar (verb) / artigo (noun) | запись (noun) | 文章 (noun) |  |

- `ar`: Glossary 'post' = 'مقالة'.
- `fr_FR`: Glossary 'post' = 'publication'.
- `it_IT`: Glossary 'post' = 'articolo'.
- `ru_RU`: Glossary 'post' = 'запись', 'id' = 'id' (untranslatable).

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | مُعرّف المقالة غير صالح |
| `de_DE` | Ungültige Beitrags-ID |
| `es_ES` | ID de entrada no válido |
| `fr_FR` | ID de publication invalide |
| `it_IT` | ID articolo non valido |
| `ja` | 無効な投稿 ID |
| `pt_PT` | ID de artigo inválido |
| `ru_RU` | Неверный ID записи |
| `zh_CN` | 无效的文章 ID |

### 144. `Failed to get Contact Form fields`

- **Used in:** AJAX `query_cf7_fields` exception
- **Usage context:** Error: the fields of the Contact Form 7 form could not be retrieved.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| contact form | – | – | – | – | – | お問い合わせフォーム (noun) | – | – | – |  |
| fields | حقول (noun) | – | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | فشل الحصول على حقول نموذج الاتصال |
| `de_DE` | Die Felder des Kontaktformulars konnten nicht abgerufen werden |
| `es_ES` | No se han podido obtener los campos del formulario de contacto |
| `fr_FR` | Impossible d’obtenir les champs du formulaire de contact |
| `it_IT` | Impossibile ottenere i campi del modulo di contatto |
| `ja` | お問い合わせフォームのフィールドを取得できませんでした |
| `pt_PT` | Falha ao obter os campos do formulário de contacto |
| `ru_RU` | Не удалось получить поля контактной формы |
| `zh_CN` | 无法获取联系表单字段 |

### 145. `Failed to determine upload path`

- **Used in:** AJAX `query_page_image` / save directory
- **Usage context:** Error: the wp-content/uploads path could not be determined.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

Upload path refers to the server filesystem location used for uploaded files, not a download link or remote URL. Preserve the failure to determine that path without implying that a file upload was attempted and rejected.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| path | – | – | ruta (noun) | – | percorso (noun) | – | – | – | 路径 (noun) |  |
| upload | رفع (verb) | Upload (noun) / hochladen (verb) | subida (noun) / subir (verb) | téléverser (verb) | carica (verb) / upload (noun) | – | carregamento (noun) / carregar (verb) | загрузить (verb) | 上传 (verb) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | فشل تحديد مسار الرفع |
| `de_DE` | Der Upload-Pfad konnte nicht ermittelt werden |
| `es_ES` | No se ha podido determinar la ruta de subida |
| `fr_FR` | Impossible de déterminer le chemin de téléversement |
| `it_IT` | Impossibile determinare il percorso di upload |
| `ja` | アップロードパスを特定できませんでした |
| `pt_PT` | Falha ao determinar o caminho de carregamento |
| `ru_RU` | Не удалось определить путь для загрузки |
| `zh_CN` | 无法确定上传路径 |

### 146. `Invalid page number`

- **Used in:** AJAX `query_page_image` exception
- **Usage context:** Error: invalid PDF page number requested for the page preview.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| invalid | غير صالح (adjective) / غير صالحة (adjective) | ungültig (adjective) | no válido (noun) | invalide (adjective) | non valido (adjective) | 無効 (adjective) / 不正な (adjective) / 間違った (adjective) | – | – | 无效 (adjective) |  |
| page | صفحة (noun) | Seite (noun) | página (noun) / página (noun) | page (noun) | pagina (noun) | ページ (noun) / 固定ページ (noun) | – | – | 页面 (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | رقم الصفحة غير صالح |
| `de_DE` | Ungültige Seitenzahl |
| `es_ES` | Número de página no válido |
| `fr_FR` | Numéro de page invalide |
| `it_IT` | Numero di pagina non valido |
| `ja` | 無効なページ番号 |
| `pt_PT` | Número de página inválido |
| `ru_RU` | Неверный номер страницы |
| `zh_CN` | 无效的页码 |

### 147. `Failed to retrieve page snapshot`

- **Used in:** AJAX `query_page_image` exception
- **Usage context:** Error: the preview image (snapshot) of the PDF page could not be retrieved from the service.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

Snapshot means the rendered preview of a PDF page. Use page-preview terminology rather than an operating-system shadow copy or backup.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| page | صفحة (noun) | Seite (noun) | página (noun) / página (noun) | page (noun) | pagina (noun) | ページ (noun) / 固定ページ (noun) | – | – | 页面 (noun) |  |
| retrieve | – | – | recuperar (verb) | – | – | – | – | – | 截取 (noun) |  |
| snapshot | – | – | instantánea (noun) | instantané (noun) | – | – | – | – | 快照 (noun) |  |

- `zh_CN`: Glossary 'retrieve' = '截取', 'snapshot' = '快照'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | فشل استرداد لقطة الصفحة |
| `de_DE` | Die Momentaufnahme der Seite konnte nicht abgerufen werden |
| `es_ES` | No se ha podido recuperar la instantánea de la página |
| `fr_FR` | Impossible de récupérer l’instantané de la page |
| `it_IT` | Impossibile recuperare l'istantanea della pagina |
| `ja` | ページのスナップショットを取得できませんでした |
| `pt_PT` | Falha ao obter o instantâneo da página |
| `ru_RU` | Не удалось получить снимок страницы |
| `zh_CN` | 无法截取页面快照 |

### 148. `PDF Form`

- **Used in:** CF7 tag generator button label (`add_tag_generator`)
- **Usage context:** Label of the button in the CF7 form editor toolbar (next to 'text', 'email', ...) that opens the plugin's tag generator dialog.
- **UI space limit:** Visible CF7 toolbar button measures about 73×26px for English PDF Form. The button is content-driven and the toolbar can reflow; no fixed 12-character limit was observed.

**Research / typical translations**

This CF7 form-editor toolbar button opens the PDF form-tag generator. Translate PDF Form as a form concept rather than a PDF file format.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | نموذج PDF |
| `de_DE` | PDF-Formular |
| `es_ES` | Formulario PDF |
| `fr_FR` | Formulaire PDF |
| `it_IT` | Modulo PDF |
| `ja` | PDF フォーム |
| `pt_PT` | Formulário PDF |
| `ru_RU` | PDF-форма |
| `zh_CN` | PDF 表单 |

### 149. `Go to PDF files`

- **Used in:** Tag generator buttons
- **Usage context:** Button that switches to the PDF Forms Filler panel (PDF attachments section).
- **UI space limit:** BUTTON label, measured 92px wide (h24, small) for 'Go to PDF files'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | الانتقال إلى ملفات PDF | not measured |
| `de_DE` | Zu den PDF-Dateien | not measured |
| `es_ES` | Ir a los archivos PDF | not measured |
| `fr_FR` | Aller aux fichiers PDF | not measured |
| `it_IT` | Vai ai file PDF | not measured |
| `ja` | PDF ファイルへ移動 | not measured |
| `pt_PT` | Ir para os ficheiros PDF | not measured |
| `ru_RU` | Перейти к PDF-файлам | not measured |
| `zh_CN` | 转到 PDF 文件 | not measured |

### 150. `Go to Field Mappings`

- **Used in:** Tag generator buttons
- **Usage context:** Button that switches to the PDF Forms Filler panel (field mapper section).
- **UI space limit:** BUTTON label, measured 123px wide (h24, small) for 'Go to Field Mappings'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| field | – | – | – | – | – | – | – | – | 字段 (noun) |  |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | الانتقال إلى تعيينات الحقول | not measured |
| `de_DE` | Zu den Feldzuordnungen | not measured |
| `es_ES` | Ir a las asignaciones de campos | not measured |
| `fr_FR` | Aller aux correspondances de champs | not measured |
| `it_IT` | Vai alle mappature dei campi | not measured |
| `ja` | フィールドマッピングへ移動 | not measured |
| `pt_PT` | Ir para os mapeamentos de campos | not measured |
| `ru_RU` | Перейти к сопоставлениям полей | not measured |
| `zh_CN` | 转到字段映射 | not measured |

### 151. `Insert Tags`

- **Used in:** Tag generator button (deprecated tool)
- **Usage context:** Button that inserts the generated tags (textarea content) into the form.
- **UI space limit:** BUTTON label, measured (primary, h40) for 'Insert Tags'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

CF7 'Insert Tag': ar 'إدراج الوسم', de 'Tag einfügen', es 'Insertar', fr 'Insérer la balise', it 'Inserisci tag', ja 'タグを挿入', pt 'Inserir etiqueta', ru 'Вставить тег', zh '插入标签'.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| insert | إدراج (verb) | – | – | – | – | – | – | – | 插入 (verb) |  |
| tags | وسوم (noun) | Schlagwörter (noun) / Tags (noun) | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | إدراج الوسوم | not measured |
| `de_DE` | Tags einfügen | not measured |
| `es_ES` | Insertar etiquetas | not measured |
| `fr_FR` | Insérer les balises | not measured |
| `it_IT` | Inserisci tag | not measured |
| `ja` | タグを挿入 | not measured |
| `pt_PT` | Inserir etiquetas | not measured |
| `ru_RU` | Вставить теги | not measured |
| `zh_CN` | 插入标签 | not measured |

### 152. `The 'Insert & Link All' button allows you to generate tags for all remaining unlinked PDF fields, insert them into the form and link them to their corresponding fields.`

- **Used in:** Tag generator footer
- **Usage context:** Explanation of the 'Insert & Link All' button; the quoted button name must match #119 exactly.
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| allows you | – | – | – | vous permet de (verb) | – | – | – | – | – |  |
| button | زر (noun) | Button (noun) | – | – | pulsante (noun) | – | – | – | 按钮 (noun) |  |
| fields | حقول (noun) | – | – | – | – | – | – | – | – |  |
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) |  |
| insert | إدراج (verb) | – | – | – | – | – | – | – | 插入 (verb) |  |
| link | رابط (noun) | Link (noun) | enlazar (verb) / enlace (noun) | Lien (noun) | link (noun) / collega (verb) | – | ligação (noun) | – | 链接 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |
| tags | وسوم (noun) | Schlagwörter (noun) / Tags (noun) | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | يتيح لك زر «إدراج الكل وربطه» إنشاء وسوم لجميع حقول PDF المتبقية غير المرتبطة، وإدراجها في النموذج وربطها بالحقول المقابلة لها. |
| `de_DE` | Mit dem Button „Alle einfügen und verknüpfen“ kannst du Tags für alle verbleibenden, nicht verknüpften PDF-Felder generieren, sie in das Formular einfügen und mit den entsprechenden Feldern verknüpfen. |
| `es_ES` | El botón «Insertar y enlazar todo» te permite generar etiquetas para todos los campos PDF restantes sin enlazar, insertarlas en el formulario y enlazarlas con sus campos correspondientes. |
| `fr_FR` | Le bouton «&nbsp;Tout insérer et lier&nbsp;» vous permet de générer des balises pour tous les champs PDF restants non liés, de les insérer dans le formulaire et de les lier à leurs champs correspondants. |
| `it_IT` | Il pulsante 'Inserisci e collega tutto' ti consente di generare i tag per tutti i campi PDF rimanenti non collegati, inserirli nel modulo e collegarli ai campi corrispondenti. |
| `ja` | 「すべて挿入してリンク」ボタンを使用すると、リンクされていない残りのすべての PDF フィールドのタグを生成し、フォームに挿入して、対応するフィールドにリンクできます。 |
| `pt_PT` | O botão 'Inserir e ligar tudo' permite gerar etiquetas para todos os campos PDF restantes não ligados, inseri-las no formulário e ligá-las aos campos correspondentes. |
| `ru_RU` | Кнопка «Вставить и связать все» позволяет сгенерировать теги для всех оставшихся несвязанных полей PDF, вставить их в форму и связать с соответствующими полями. |
| `zh_CN` | “全部插入并链接”按钮可为所有剩余未链接的 PDF 字段生成标签，将其插入表单并链接到相应的字段。 |

### 153. `Field Mapping Generator`

- **Used in:** Tag generator tool heading
- **Usage context:** Name of the tool in the tag generator that creates a form-tag for a selected PDF field.
- **UI space limit:** Tool heading in the 720px PDF Form dialog content at the reference viewport; text can wrap. Check its combined width with the toggle/help controls.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| field | – | – | – | – | – | – | – | – | 字段 (noun) |  |
| generator | – | – | – | – | – | – | – | – | 生成器 (noun) |  |
| mapping | – | – | asignación (expression) | – | – | – | mapeamento (noun) / mapear (verb) | – | 映射 (adjective) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | مولّد تعيينات الحقول |
| `de_DE` | Feldzuordnungs-Generator |
| `es_ES` | Generador de asignaciones |
| `fr_FR` | Générateur de correspondances |
| `it_IT` | Generatore di mappature |
| `ja` | フィールドマッピングジェネレーター |
| `pt_PT` | Gerador de mapeamentos |
| `ru_RU` | Генератор сопоставлений |
| `zh_CN` | 字段映射生成器 |

### 154. `This tool can be used to generate form-tags based on PDF fields after attaching a PDF file with a form.`

- **Used in:** Tag generator help box
- **Usage context:** Help text of the field mapping generator.
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| fields | حقول (noun) | – | – | – | – | – | – | – | – |  |
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) |  |
| on | – | – | – | – | on (noun) | – | – | – | – | it_IT glossary 'on' is the switch state; here a preposition |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |
| tool | – | Werkzeug (noun) | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | يمكن استخدام هذه الأداة لإنشاء وسوم النموذج استنادًا إلى حقول PDF بعد إرفاق ملف PDF يحتوي على نموذج. |
| `de_DE` | Mit diesem Werkzeug kannst du Formular-Tags auf Basis von PDF-Feldern generieren, nachdem du eine PDF-Datei mit einem Formular angehängt hast. |
| `es_ES` | Esta herramienta se puede usar para generar etiquetas de formulario basadas en campos PDF después de adjuntar un archivo PDF con un formulario. |
| `fr_FR` | Cet outil permet de générer des balises de formulaire à partir des champs PDF après avoir joint un fichier PDF contenant un formulaire. |
| `it_IT` | Questo strumento può essere utilizzato per generare form-tag basati sui campi PDF dopo aver allegato un file PDF con un modulo. |
| `ja` | このツールを使用すると、フォーム付きの PDF ファイルを添付した後、PDF フィールドに基づいてフォームタグを生成できます。 |
| `pt_PT` | Esta ferramenta pode ser utilizada para gerar etiquetas de formulário com base em campos PDF depois de anexar um ficheiro PDF com um formulário. |
| `ru_RU` | Этот инструмент позволяет генерировать теги формы на основе полей PDF после прикрепления PDF-файла с формой. |
| `zh_CN` | 此工具可用于在附加带有表单的 PDF 文件后，根据 PDF 字段生成表单标签。 |

### 155. `Tag Generator Tool (deprecated)`

- **Used in:** Tag generator tool heading
- **Usage context:** Name of the deprecated legacy tag generator tool.
- **UI space limit:** Tool heading in the 720px PDF Form dialog content at the reference viewport; text can wrap. Check its combined width with the toggle/help controls.

**Research / typical translations**

The heading denotes a CF7 form-tag generator with deprecated status. Preserve tag generation and the technical deprecation term; do not substitute code generation, disapproval or an exam-result concept.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| deprecated | مهجور (adjective) | veraltet (adjective) | obsoleto (adjective) | obsolète (adjective) | deprecato (adjective) | 非推奨 (adjective) | – | – | 已弃用 (adjective) |  |
| generator | – | – | – | – | – | – | – | – | 生成器 (noun) |  |
| tag | وسم (noun) | Schlagwort (noun) / Tag (noun) | etiqueta (noun) | étiquette (noun) / balise (noun) | tag (noun) / tagga (verb) | タグ (noun) | elemento (noun) / etiqueta (noun) | метка (noun) | 标签 (noun) / 标记 (verb) |  |
| tool | – | Werkzeug (noun) | – | – | – | – | – | – | – |  |

- `ar`: Glossary 'deprecated' = 'مهجور'.
- `it_IT`: Glossary 'deprecated' = 'deprecato'.
- `ja`: Glossary 'deprecated' = '非推奨'.
- `zh_CN`: Glossary 'deprecated' = '已弃用'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | مولّد الوسوم (مهجور) |
| `de_DE` | Tag-Generator (veraltet) |
| `es_ES` | Generador de etiquetas (obsoleto) |
| `fr_FR` | Générateur de balises (obsolète) |
| `it_IT` | Generatore di tag (deprecato) |
| `ja` | タグジェネレーター (非推奨) |
| `pt_PT` | Gerador de etiquetas de variáveis (obsoleto) |
| `ru_RU` | Генератор тегов (устарел) |
| `zh_CN` | 标签生成器（已弃用） |

### 156. `This tool allows creation of CF7 fields that are linked to PDF fields by name. This feature is deprecated in favor of the field mapper tool.`

- **Used in:** Tag generator help box
- **Usage context:** Help text of the deprecated tag generator; refers to the field mapper tool (#111).
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| deprecated | مهجور (adjective) | veraltet (adjective) | obsoleto (adjective) | obsolète (adjective) | deprecato (adjective) | 非推奨 (adjective) | – | – | 已弃用 (adjective) |  |
| feature | – | Funktion (noun) / hervorheben (verb) | característica (noun) / destacar (verb) | fonctionnalité (noun) | caratteristica (noun) | – | recurso (ou funcionalidade) (noun) | – | – |  |
| fields | حقول (noun) | – | – | – | – | – | – | – | – |  |
| name | اسم (noun) | – | – | – | – | – | – | – | – |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |
| tool | – | Werkzeug (noun) | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تتيح هذه الأداة إنشاء حقول CF7 مرتبطة بحقول PDF حسب الاسم. هذه الميزة مهجورة، ويُفضَّل استخدام أداة تعيين الحقول بدلًا منها. |
| `de_DE` | Mit diesem Werkzeug können CF7-Felder erstellt werden, die über den Namen mit PDF-Feldern verknüpft sind. Diese Funktion ist veraltet und wurde durch das Werkzeug zur Feldzuordnung ersetzt. |
| `es_ES` | Esta herramienta permite crear campos de CF7 enlazados por nombre con campos PDF. Esta característica está obsoleta en favor de la herramienta de asignación de campos. |
| `fr_FR` | Cet outil permet de créer des champs CF7 liés aux champs PDF par leur nom. Cette fonctionnalité est obsolète au profit de l’outil de correspondance des champs. |
| `it_IT` | Questo strumento consente di creare campi CF7 collegati per nome ai campi PDF. Questa caratteristica è deprecata a favore dello strumento di mappatura dei campi. |
| `ja` | このツールでは、名前によって PDF フィールドにリンクされた CF7 フィールドを作成できます。この機能は非推奨となり、フィールドマッピングツールに置き換えられました。 |
| `pt_PT` | Esta ferramenta permite criar campos do CF7 ligados a campos PDF pelo nome. Esta funcionalidade está obsoleta e foi substituída pela ferramenta de mapeamento de campos. |
| `ru_RU` | Этот инструмент позволяет создавать поля CF7, связанные с полями PDF по имени. Эта функция устарела, вместо неё используйте инструмент сопоставления полей. |
| `zh_CN` | 此工具可创建按名称链接到 PDF 字段的 CF7 字段。此功能已弃用，请改用字段映射工具。 |

### 157. `The PDF file attachment tool, the field mapper tool and the image embedding tool have moved to the {a-href-panel-link}PDF Forms Filler panel{/a}.`

- **Used in:** Tag generator notice
- **Usage context:** Notice that tools moved to the PDF Forms Filler panel (#97). {a-href-panel-link}...{/a} links to the panel. The panel name is the localized short name from #97, quoted with the locale’s quotation marks.
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| attachment | مُرفق (noun) | Anhang (noun) | adjunto (noun) | fichier joint (noun) | allegato (noun) | 添付ファイル (noun) | – | вложение (noun) | – |  |
| field | – | – | – | – | – | – | – | – | 字段 (noun) |  |
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| forms | نماذج (noun) | – | – | – | – | – | – | – | – | part of the localized panel name (#97) |
| image | – | – | – | – | immagine (noun) | – | – | – | 图片 (noun) |  |
| panel | – | – | – | – | – | – | – | – | 面板 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |
| tool | – | Werkzeug (noun) | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | تم نقل أداة إرفاق ملفات PDF وأداة تعيين الحقول وأداة تضمين الصور إلى {a-href-panel-link}لوحة «أداة تعبئة نماذج PDF»{/a}. |
| `de_DE` | Das Werkzeug zum Anhängen von PDF-Dateien, das Werkzeug zur Feldzuordnung und das Werkzeug zur Bildeinbettung wurden in das {a-href-panel-link}Panel „PDF-Formularausfüller“{/a} verschoben. |
| `es_ES` | La herramienta para adjuntar archivos PDF, la herramienta de asignación de campos y la herramienta de incrustación de imágenes se han trasladado al {a-href-panel-link}panel «Herramienta de relleno de formularios PDF»{/a}. |
| `fr_FR` | L’outil pour joindre des fichiers PDF, l’outil de correspondance des champs et l’outil d’images embarquées ont été déplacés vers le {a-href-panel-link}panneau «&nbsp;Outil de remplissage de formulaires PDF&nbsp;»{/a}. |
| `it_IT` | Lo strumento per allegare file PDF, lo strumento di mappatura dei campi e lo strumento di incorporamento immagini sono stati spostati nel {a-href-panel-link}pannello 'Strumento di compilazione moduli PDF'{/a}. |
| `ja` | PDF ファイル添付ツール、フィールドマッピングツール、画像埋め込みツールは{a-href-panel-link}「PDF フォーム入力ツール」パネル{/a}に移動しました。 |
| `pt_PT` | A ferramenta para anexar ficheiros PDF, a ferramenta de mapeamento de campos e a ferramenta de incorporação de imagens foram movidas para o {a-href-panel-link}painel 'Ferramenta de preenchimento de formulários PDF'{/a}. |
| `ru_RU` | Инструмент прикрепления PDF-файлов, инструмент сопоставления полей и инструмент встраивания изображений перемещены на {a-href-panel-link}панель «Инструмент заполнения PDF-форм»{/a}. |
| `zh_CN` | PDF 文件附加工具、字段映射工具和图片嵌入工具已移至{a-href-panel-link}“PDF 表单填写工具”面板{/a}。 |

### 158. `Get Tags`

- **Used in:** Tag generator button (deprecated tool)
- **Usage context:** Button that generates tags for the PDF fields into the textarea.
- **UI space limit:** BUTTON label, measured (primary, h40) for 'Get Tags'. Reference caption size only: the control grows with its content. Check the rendered control and neighbouring controls within their actual container; no character-count maximum is established.

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| tags | وسوم (noun) | Schlagwörter (noun) / Tags (noun) | – | – | – | – | – | – | – |  |

**Adopted translations**

| Locale | Translation | Measured fit |
|---|---|---|
| `ar` | الحصول على الوسوم | not measured |
| `de_DE` | Tags abrufen | not measured |
| `es_ES` | Obtener etiquetas | not measured |
| `fr_FR` | Récupérer les balises | not measured |
| `it_IT` | Ottieni tag | not measured |
| `ja` | タグを取得 | not measured |
| `pt_PT` | Obter etiquetas | not measured |
| `ru_RU` | Получить теги | not measured |
| `zh_CN` | 获取标签 | not measured |

### 159. `PDF form fields`

- **Used in:** Tag generator dialog heading (CF7 v6)
- **Usage context:** Heading of the tag generator dialog.
- **UI space limit:** No hard limit (help paragraph; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| fields | حقول (noun) | – | – | – | – | – | – | – | – |  |
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | حقول نموذج PDF |
| `de_DE` | PDF-Formularfelder |
| `es_ES` | Campos de formulario PDF |
| `fr_FR` | Champs de formulaire PDF |
| `it_IT` | Campi del modulo PDF |
| `ja` | PDF フォームフィールド |
| `pt_PT` | Campos de formulário PDF |
| `ru_RU` | Поля PDF-формы |
| `zh_CN` | PDF 表单字段 |

### 160. `Once you {a-href-panel-link}attach a PDF file with fields to your form{/a}, you will be able to generate form-tags that are linked to PDF form fields.`

- **Used in:** Tag generator dialog description (CF7 v6)
- **Usage context:** Description; {a-href-panel-link}...{/a} links to the PDF Forms Filler panel.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| fields | حقول (noun) | – | – | – | – | – | – | – | – |  |
| file | – | – | archivo (noun) | – | file (noun) | – | ficheiro (noun) | – | 文件 (noun) |  |
| form | نموذج (noun) | – | – | formulaire (noun) | modulo (noun) | – | – | – | 表单 (noun) |  |
| pdf | – | – | – | – | – | – | – | – | PDF (noun) |  |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | بمجرد {a-href-panel-link}إرفاق ملف PDF يحتوي على حقول بنموذجك{/a}، ستتمكن من إنشاء وسوم نماذج مرتبطة بحقول نموذج PDF. |
| `de_DE` | Sobald du {a-href-panel-link}eine PDF-Datei mit Feldern an dein Formular angehängt hast{/a}, kannst du Formular-Tags generieren, die mit PDF-Formularfeldern verknüpft sind. |
| `es_ES` | Una vez que {a-href-panel-link}adjuntes un archivo PDF con campos a tu formulario{/a}, podrás generar etiquetas de formulario enlazadas con los campos del formulario PDF. |
| `fr_FR` | Une fois que vous aurez {a-href-panel-link}joint un fichier PDF avec des champs à votre formulaire{/a}, vous pourrez générer des balises de formulaire liées aux champs du formulaire PDF. |
| `it_IT` | Dopo aver {a-href-panel-link}allegato un file PDF con campi al tuo modulo{/a}, potrai generare form-tag collegati ai campi del modulo PDF. |
| `ja` | {a-href-panel-link}フィールドを含む PDF ファイルをフォームに添付{/a}すると、PDF フォームフィールドにリンクされたフォームタグを生成できるようになります。 |
| `pt_PT` | Depois de {a-href-panel-link}anexar um ficheiro PDF com campos ao seu formulário{/a}, poderá gerar etiquetas de formulário ligadas aos campos do formulário PDF. |
| `ru_RU` | После того как вы {a-href-panel-link}прикрепите к форме PDF-файл с полями{/a}, вы сможете генерировать теги формы, связанные с полями PDF-формы. |
| `zh_CN` | {a-href-panel-link}将带有字段的 PDF 文件附加到表单{/a}后，您就可以生成链接到 PDF 表单字段的表单标签。 |

### 161. `Your CF7 plugin is too out of date, please upgrade.`

- **Used in:** Unsupported CF7 version tag generator
- **Usage context:** Shown when the installed CF7 version does not support the tag generator.
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

The message asks for a Contact Form 7 upgrade because the installed version lacks required tag-generator support. Preserve both the version-compatibility cause and the upgrade action.

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| please | – | – | – | Veuillez (noun) | NON SI TRADUCE (noun) | – | – | – | – |  |
| plugin | إضافة (noun) | Plugin (noun) | plugin (noun) | extension (noun) | plugin (noun) | プラグイン (noun) | plugin (noun) | – | 插件 (noun) |  |
| upgrade | – | Upgrade (noun) / Upgrade installieren (verb) | actualización (noun) / actualizar (verb) | mettre à niveau (verb) / mise à niveau (noun) | – | アップグレード (noun) | – | – | 升级 (verb) |  |

- `fr_FR`: Glossary 'upgrade' = 'mettre à niveau'.

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | نسخة الإضافة CF7 المثبّتة لديك قديمة جدًا، يُرجى الترقية. |
| `de_DE` | Dein CF7-Plugin ist zu veraltet, bitte installiere ein Upgrade. |
| `es_ES` | Tu plugin CF7 está demasiado desactualizado, por favor, actualízalo. |
| `fr_FR` | Votre extension CF7 est trop ancienne, veuillez la mettre à niveau. |
| `it_IT` | Il tuo plugin CF7 è troppo obsoleto, aggiornalo. |
| `ja` | CF7 プラグインが古すぎます。アップグレードしてください。 |
| `pt_PT` | O seu plugin CF7 está demasiado desactualizado, por favor actualize-o. |
| `ru_RU` | Ваш плагин CF7 слишком устарел, пожалуйста, обновите его. |
| `zh_CN` | 您的 CF7 插件版本过旧，请升级。 |

### 162. `{icon} {a-href-url}{filename}{/a} {i}({size}){/i}`

- **Used in:** Download link in the form submission response
- **Usage context:** Layout pattern of a download link: {icon} = download icon, {a-href-url}{filename}{/a} = link, {i}({size}){/i} = file size. Usually no translation needed except for RTL/punctuation conventions (full-width parentheses not wanted).
- **UI space limit:** No hard limit (notice; wraps).

**Research / typical translations**

No identical string exists in Contact Form 7 / WooCommerce / WordPress core; the translation is composed from the terminology matrix above (CF7/core terms for the key words).

**WordPress glossary terms**

| Term | `ar` | `de_DE` | `es_ES` | `fr_FR` | `it_IT` | `ja` | `pt_PT` | `ru_RU` | `zh_CN` | Note |
|---|---|---|---|---|---|---|---|---|---|---|
| filename | – | – | – | – | – | – | – | – | 文件名 (noun) | only part of a product name / URL / placeholder – not translated |
| icon | أيقونة (noun) | – | – | Icône (noun) | icona (noun) | – | – | – | 图标 (noun) | only part of a product name / URL / placeholder – not translated |
| size | حجم (noun) | – | – | taille (noun) | – | – | – | – | – | only part of a product name / URL / placeholder – not translated |

**Adopted translations**

| Locale | Translation |
|---|---|
| `ar` | {icon} {a-href-url}{filename}{/a} {i}({size}){/i} |
| `de_DE` | {icon} {a-href-url}{filename}{/a} {i}({size}){/i} |
| `es_ES` | {icon} {a-href-url}{filename}{/a} {i}({size}){/i} |
| `fr_FR` | {icon} {a-href-url}{filename}{/a} {i}({size}){/i} |
| `it_IT` | {icon} {a-href-url}{filename}{/a} {i}({size}){/i} |
| `ja` | {icon} {a-href-url}{filename}{/a} {i}({size}){/i} |
| `pt_PT` | {icon} {a-href-url}{filename}{/a} {i}({size}){/i} |
| `ru_RU` | {icon} {a-href-url}{filename}{/a} {i}({size}){/i} |
| `zh_CN` | {icon} {a-href-url}{filename}{/a} {i}({size}){/i} |

## Layout reference and validation constraints

Reference environment: Chrome at 1280×800 CSS pixels, WordPress 7.1 admin, CF7 editor and PDF Form dialog, and Pdf.Ninja integration summary/edit forms. Dimensions are approximate and depend on fonts, stylesheet versions, locale and neighbouring controls. Per-locale “not measured” entries denote absent measurement evidence, not a failed fit result.

### Editor panel and PDF Form dialog

| Consumer | Reference geometry and translation constraint |
|---|---|
| PDF Forms Filler panel | 744px content width. Complete localized captions, including input-button values, must remain within the panel. |
| PDF Forms Filler panel tab | English caption ~132×34px. The localized short product names (#97) are longer; their tab-row fit beside localized CF7 tabs is not measured. |
| PDF Form dialog | 720px content width. Heading, icon, show/hide caption and help control share a row; validate their rectangles for overlap in both collapsed and expanded states. |
| Portuguese generator row | Heading ~275px, full hide caption ~253px, help control ~92px; the complete row fits at the reference viewport. Use etiquetas de variáveis for the CF7 form-tag generator. |
| French mapping action | Ajouter une correspondance is ~201×40px; its table cell reflows within the panel. The English button width is not a maximum. |
| French image action | Embarquer une image is ~164×40px in a ~172px cell. Preserve the grammatical article and glossary verb where this layout permits them. |
| Source headers | English CF7 field/mail-tags cells are ~291×58px in the mapper and ~223×59px in the image tool. Usable width excludes padding. |
| Spanish source header | Campo CF7/etiquetas de correo electrónico wraps; mapper header row ~76px high and image-tool header row ~79px high. Preserve correo electrónico rather than treating an English-derived character budget as a limit. |
| Portuguese source header | Campo CF7/etiquetas de correio fits the mapper; the image-tool header wraps to a ~79px row. |
| PDF-field headers | PDF field has separate consumers: mapper header ~291×58px; tag-generator row header ~210×58px. |
| Filename options | Variable-height text blocks allow explicit mail-tag permission. Reference text widths: Arabic ~169px, Spanish ~430px, French ~272px, Italian ~265px, Portuguese ~384px, Russian ~325px. Validate each within its complete option area. |
| Japanese attachment targets | メインメールに添付 ~131px; メール (2) に添付 ~112px. Use CF7’s second-template label rather than assuming an autoresponder role. |
| PDF Form toolbar button | Visible English control ~73×26px; measure it with the Form tab active. Hidden-state dimensions do not describe available space. |

### Integration summary: API URL

The summary card uses a fixed-layout table. Its header cells are 170px wide, with 10px right padding and approximately 160px usable text width. The adjacent value cell is 293px; the table occupies the card’s 463px content area within a 520px outer card.

| Caption | Rendered lines | Header-row height | Container fit |
|---|---:|---:|---|
| English API URL | 1 | ~48px | Table remains 463px wide. |
| Arabic عنوان URL لواجهة برمجة التطبيقات (API) | 2 | ~66px | No cell overflow; table remains 463px wide inside the card. |

The Arabic glossary expansion fits because the row height can increase; it does not fit on one line. Local RTL direction preserves the two-line result and table dimensions. Check the actual Arabic admin stylesheet and supported viewports independently before treating this reference as a broader RTL guarantee.

### Integration edit forms

The edit card has a 463px content area. Its ordinary table columns depend on text and the minimum width of the regular-text input. A label can be unclipped while the table extends outside the form content: compare the table rectangle with the content rectangle, not just the label’s scroll width. A taller row does not necessarily reduce the minimum width imposed by an unbreakable word.

The administrator-address row's table width (~486-498px) is driven by the 350px `regular-text` input in the adjacent cell, not by the label text. The label cell wraps freely and no candidate translation is clipped; even the English original overflows the 463px form content area. Label wording therefore does not control container fit for this row.

| Label/content | Reference geometry |
|---|---|
| English administrator-address label | Text ~104×35px over 2 lines; header cell ~114×69px; table ~486px (input-driven). |
| Arabic API URL | Header cell 91px, usable text width 81px, 3 lines, row ~88px; table remains 463px wide. |
| Arabic administrator-address label | Row ~88px; table remains 463px wide. |
| Japanese 管理者メールアドレス | Row ~69px; table remains 463px wide. |
| Spanish Correo electrónico del administrador | Row ~108px and ~104px cell; table ~476px (input-driven). |
| Russian Адрес электронной почты администратора | 4 lines, row ~103px, cell ~114px, unclipped; table ~498px (input-driven). |

The table records the minimum-width behavior of these wording candidates; it does not establish glossary compliance or container fit. A wording decision must satisfy glossary/context requirements and container fit together. Inclusive French administrator terminology requires the appropriate combined form in sentences. When natural compliant wording cannot fit, evaluate a layout solution or a clearly justified contextual abbreviation rather than silently accepting overflow.

### Measurement scope

Text-substitution measurements assess font and markup behavior without persisting WordPress settings. Translate the complete UI, including input captions and open help/toggle states, and restore the page after measurement. Local RTL direction does not load WordPress’s RTL stylesheet. Other viewports, zoom levels, operating-system fonts and legacy CF7 dialogs require separate validation. Keep persistent documentation focused on current reference facts and constraints, not task history.
