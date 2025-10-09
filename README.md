# XML to Google Sheets Data Processor

## Description

A command-line application built with Symfony CLI that processes XML files (local or remote) and imports their data into Google Sheets using the Google Sheets API.

## Tech Stack

- PHP 8.2
- Symfony Console Component
- Google Sheets API
- Docker & Docker Compose

## Prerequisites

- Docker and Docker Compose installed
- Google Cloud Console account

## Installation

### 1. Google Cloud Setup

1. Create a new project in [Google Cloud Console](https://console.cloud.google.com/)
2. Enable the **Google Sheets API** for your project
3. Create a **Service Account** and download the credentials JSON file

### 2. Application Setup

1. Copy the environment template:

   ```bash
   cp .env.example .env
   ```

2. Configure environment variables in `.env`:

   - `GOOGLE_AUTH_CONFIG`: Path to your credentials JSON file
   - `GOOGLE_SHEET_ID`: Your target Google Sheet ID

3. Share your Google Sheet with the service account email (found in credentials JSON)

4. Build and start Docker containers:

   ```bash
   docker-compose up --build
   ```

5. Install dependencies:
   ```bash
   docker-compose exec app bash
   composer install
   ```

## Usage

### Basic Command

```bash
bin/console xml:feed-data <xml-source> <target-node> [options]
```

### Parameters

- `<xml-source>`: URL or local path to XML file
- `<target-node>`: XML node to parse (default: `item`)

### Options

- `--header`: Include header row (default: enabled)
- `--no-header`: Exclude header row

### Examples

```bash
# With header (default)
bin/console xml:feed-data "https://www.w3schools.com/xml/plant_catalog.xml" PLANT

# Without header
bin/console xml:feed-data "https://www.w3schools.com/xml/plant_catalog.xml" PLANT --no-header
```

## Architecture

### Design Patterns

**Singleton Pattern**

- Ensures single Google Client instance throughout application lifecycle

**Strategy Pattern**

- Modular file parsing for different formats (XML, CSV, JSON)
- Easy to extend with new file type implementations
- Located in `App\Strategy` namespace

**Factory Pattern**

- Dynamic strategy instantiation without manual object creation

### Memory Optimization

**Generator Pattern** (Default)

- Uses PHP generators to yield data row-by-row
- Memory-efficient for large files
- Prevents loading entire dataset into memory

**Array Implementation** (Alternative)

- Traditional array-based processing
- Faster for small to medium files
- Higher memory consumption

#### Switching Implementations

Edit `config/services.yaml`:

```yaml
App\Strategy\XmlParserStrategy:
  arguments:
    $xmlReaderAbstract: '@App\Reader\Xml\XmlArrayReader' # For array mode
    # $xmlReaderAbstract: '@App\Reader\Xml\XmlGeneratorReader'  # For generator mode (default)
```

### SOLID Principles

**Single Responsibility Principle (SRP)**

- Separate classes for reading data and writing to Google Sheets
- Each service handles one specific concern

**Open/Closed Principle**

- Extensible strategy system for new file formats
- No modification needed to core logic

## Testing

Run the test suite:

```bash
php bin/phpunit
```

Clear test cache:

```bash
php bin/console cache:clear --env=test
```

## Performance Benchmarks

Tested with 6,926 rows:

- **Generator mode**: Lower memory usage, suitable for large datasets
- **Array mode**: Faster processing, higher memory consumption

## Notes

The application automatically detects whether the source is a URL or local file path, making the source type configuration redundant. However, this functionality has been implemented as specified in requirements.
