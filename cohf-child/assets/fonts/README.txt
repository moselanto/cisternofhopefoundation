Self-hosted fonts
=================

Drop two variable WOFF2 files here and the theme uses them automatically,
preloads them, and stops falling back to the system stack:

  inter-variable.woff2      (body)
  fraunces-variable.woff2   (headings)

Both are open-source (SIL Open Font License):
  Inter     - https://github.com/rsms/inter
  Fraunces  - https://github.com/undercasetype/Fraunces

No request is ever made to Google Fonts. This keeps the site fast, keeps
visitor data in Kenya's and the EU's good books, and removes a third-party
dependency. If the files are absent, the theme silently uses a high-quality
system font stack instead - nothing breaks.
