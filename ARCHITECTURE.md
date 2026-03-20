# Architecture: mime-type-detection

## Purpose

A MIME type detection library for PHP that detects media types from file extensions or binary content using the `finfo` extension.

## Directory Structure

```
src/
  Mime_Type_Detector.php               — Core interface: detect MIME from path, buffer, or file
  Extension_Lookup.php                 — Interface for reverse lookup (MIME → extension)
  Extension_To_Mime_Type_Map.php       — Interface for extension → MIME map
  Extension_Mime_Type_Detector.php     — Detects MIME by file extension only (no I/O)
  Finfo_Mime_Type_Detector.php         — Detects MIME via PHP finfo (reads file content)
  Generated_Extension_To_Mime_Type_Map.php — Large static map of 1000+ extensions to MIME types
  Overriding_Extension_To_Mime_Type_Map.php — Wraps another map, allowing user overrides
  Empty_Extension_To_Mime_Type_Map.php — Null-object map returning null for all lookups
```

## Key Design Decisions

- **Interface-first**: `Mime_Type_Detector` is an interface, allowing multiple implementations
- **Two strategies**: Extension-based (fast, no I/O) and finfo-based (accurate, reads bytes)
- **Composable maps**: `Overriding_Extension_To_Mime_Type_Map` enables user-defined overrides without subclassing
- **Generated map**: The extension→MIME map is auto-generated from IANA/Apache data and committed

## Extension Points

- Implement `Extension_To_Mime_Type_Map` to provide a custom or slimmed-down MIME map
- Implement `Mime_Type_Detector` to add a new detection strategy (e.g., magic bytes only)
- Wrap `Generated_Extension_To_Mime_Type_Map` with `Overriding_Extension_To_Mime_Type_Map` to override specific entries

## Dependency Flow

```
Mime_Type_Detector (interface)
  ├── Extension_Mime_Type_Detector  ←  Extension_To_Mime_Type_Map (interface)
  │                                       ├── Generated_Extension_To_Mime_Type_Map
  │                                       ├── Overriding_Extension_To_Mime_Type_Map
  │                                       └── Empty_Extension_To_Mime_Type_Map
  └── Finfo_Mime_Type_Detector  (uses PHP finfo extension)
```
