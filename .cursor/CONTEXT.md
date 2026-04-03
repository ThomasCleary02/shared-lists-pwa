# Shared Lists PWA — Project context

Single source of truth for humans and agents. **Rules** in `.cursor/rules/` summarize this file for the AI.

## Stack

- **Backend:** Laravel, MySQL, DDEV for local dev
- **Frontend:** Vue 3, Inertia.js, Vite (Laravel Breeze with Vue)
- **Pattern:** Monolith — no separate JSON API for page loads; controllers return `Inertia::render(...)`.

## Mental model (if you know Django / Next.js / React)

| Concept | Django-ish | This project |
|--------|------------|----------------|
| URLs + views | `urls.py` + view | `routes/web.php` + Controller method |
| ORM | Models | Eloquent models (`app/Models`) |
| Templates | Django templates | Vue pages in `resources/js/Pages` via Inertia |
| Forms POST | Form → view | `<Form>` / `router.post` → Laravel validates → `redirect()->back()` or named route |
| Auth | `request.user` | `Auth::user()`, `auth` middleware |
| Admin-only | Decorators / mixins | Policies + `authorize()` or manual checks (evolve to policies in Phase 5) |

React familiarity maps directly to Vue: `ref`/`reactive`, `<script setup>`, props from Inertia are like `getServerSideProps` data passed as props.

## Request flow

```
Browser → Laravel route → Controller → Inertia::render('Page/Name', $props) → Vue page
```

Forms submit to named Laravel routes; validate in the controller; redirect with flash or fresh props.

## Target database (MVP)

- **users** — Laravel default
- **lists** — `name`, `owner_id` (implemented; model class is `SharedList`, table `lists`)
- **list_members** — pivot: `list_id`, `user_id`, `role`, `invited_by`, `accepted_at` (pending)
- **list_items** — `list_id`, `content`, `is_complete`, `created_by` (pending)

## Target routes (auth group)

Lists: `GET/POST /lists`, `GET /lists/create`, `GET/DELETE /lists/{list}`  
Items: `POST /lists/{list}/items`, `PATCH /items/{item}`, `DELETE /items/{item}`  
Sharing: `POST /lists/{list}/invite`, `POST /invites/{membership}/accept`

## Validation (server-side)

- List `name`: required, max 255
- Item `content`: required, max 500
- Invite `email`: required, must exist in `users`

## Authorization (target)

Access if owner **or** `list_members` row with `accepted_at` not null. Otherwise **403**. (Implement via Policy in Phase 5; interim checks in controllers should align with this.)

## Development phases

1. **Setup** — Laravel, Breeze Vue, DDEV, auth ✓ (verify in your env)
2. **Lists** — model, migration, create, index, dashboard ✓ (partially; see repo status)
3. **List items** — CRUD + toggle complete
4. **Sharing** — `list_members`, invite, accept, show shared lists everywhere
5. **Authorization** — policies, consistent 403

**Out of MVP scope:** websockets, push, offline sync, public links, templates, drag-sort.

## Definition of done (MVP)

Register/login; create lists; add/remove items; share; accept invites; multi-user edit; unauthorized users cannot view/edit lists.

## Repo status (update as you ship)

- **Lists:** `SharedList` model (`lists` table), `ListController` (index, create, store, show, destroy), Vue `Lists/*`, dashboard lists props. Route model binding uses `shared_list`.
- **Not yet in schema:** `list_items`, `list_members` migrations/models.
- **Not yet implemented:** `ListItemController`, `InviteController`, item/share routes, Vue components `ListItem.vue`, `ListItemForm.vue`, `InviteUserModal.vue`.
- **Naming note:** Plan says `List`; codebase uses **`SharedList`** to avoid clashing with PHP’s `List`. Keep table name `lists`.

## Coding guidelines (agents)

- Use Eloquent relationships; migrations for all schema changes.
- Validate all input in Laravel; avoid trusting the client.
- Keep business rules out of Vue; Vue submits and displays props.
- Use Inertia responses for pages, not ad-hoc JSON APIs for the same flows.
- Check authorization before loading or mutating list data.
